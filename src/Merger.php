<?php


namespace IsaEken\Alo;


use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use IsaEken\Alo\Exceptions\FileNotExistsException;
use Microsoft\PhpParser\Node;
use Microsoft\PhpParser\Parser;
use Spatie\Regex\MatchResult;
use Spatie\Regex\Regex;

/**
 * Class Merger
 * @package IsaEken\Alo
 */
class Merger
{
    /**
     * @var Alo $alo
     */
    public Alo $alo;

    /**
     * @var Stringable $contents
     */
    public Stringable $contents;

    /**
     * @param Stringable|string $file_path
     * @return Stringable
     * @throws FileNotExistsException
     */
    private function loadFileContents(Stringable|string $file_path): Stringable
    {
        if (!($file_path instanceof Stringable)) {
            $file_path = Str::of($file_path);
        }

        $contents = Str::of(file_get_contents($file_path));
        $parser = new Parser;
        $ast_node = $parser->parseSourceFile($contents);

        foreach ($ast_node->getDescendantNodes() as $descendant) {
            if ($descendant instanceof Node\Expression) {
                if ($descendant instanceof Node\Expression\ScriptInclusionExpression) {
                    $contents = $this->mergeFirstScriptInclusion($contents, $file_path);
                }
            }
        }

        return $this->customInclusion($contents, $file_path);
    }

    /**
     * @param Stringable $contents
     * @param int $position
     * @return bool
     */
    private function isPhpOpened(Stringable $contents, int $position): bool
    {
        $parser = new Parser;
        $ast_node = $parser->parseSourceFile($contents);
        return !$ast_node->getDescendantNodeAtPosition($position) instanceof Node\Statement\InlineHtml;
    }

    /**
     * @param Stringable $contents
     * @param Stringable|string|null $current_file_path
     * @return Stringable
     * @throws FileNotExistsException
     */
    private function mergeFirstScriptInclusion(Stringable $contents, Stringable|string $current_file_path = null): Stringable
    {
        $parser = new Parser;
        $ast_node = $parser->parseSourceFile($contents);

        foreach ($ast_node->getDescendantNodes() as $descendant) {
            if ($descendant instanceof Node\Expression) {
                if ($descendant instanceof Node\Expression\ScriptInclusionExpression) {
                    $_contents = Str::of($contents->substr(0, $descendant->getFullStart()));

                    $keys = ["require_once", "include_once", "include", "require"];
                    $path = Str::of($descendant->getText());
                    $namespace = null;

                    foreach ($keys as $key) {
                        if ($path->startsWith($key)) {
                            $path = $path->substr(strlen($key));
                            break;
                        }
                    }

                    if ($descendant->getNamespaceDefinition() != null) {
                        $namespace = Str::of($descendant->getNamespaceDefinition()->getText());
                        $namespace = $namespace->after("namespace ")->beforeLast(";");
                    }

                    $cwd = getcwd();
                    $current_file_path = !($current_file_path instanceof Stringable) ? Str::of($current_file_path) : $current_file_path;
                    $path = $path
                        ->replace("__FILE__", "\"" . $current_file_path . "\"")
                        ->replace("__DIR__", "\"" . $current_file_path->beforeLast(DIRECTORY_SEPARATOR) . "\"")
                        ->replace("__NAMESPACE__", $namespace === null ? null : "\"$namespace\"")
                        ->replace("\\", "\\\\");

                    chdir($current_file_path->beforeLast(DIRECTORY_SEPARATOR));
                    if (realpath(eval("return $path;")) === false) {
                        $path = realpath(eval("return getcwd() . DIRECTORY_SEPARATOR . $path;"));
                    }
                    else {
                        $path = realpath(eval("return $path;"));
                    }
                    chdir($cwd);

                    if (! file_exists($path)) {
                        throw new FileNotExistsException;
                    }

                    $_contents = $_contents->append("?>", $this->loadFileContents($path));

                    if (!$this->isPhpOpened($_contents, $_contents->length() - 1)) {
                        $_contents = $_contents->append("<?php\r\n");
                    }

                    $_contents = $_contents->append($contents->substr($descendant->getFullStart() + $descendant->getFullWidth()));
                    $contents = $_contents;

                    break;
                }
            }
        }

        return $contents;
    }

    /**
     * @param Stringable $contents
     * @param Stringable $current_file_path
     * @return Stringable
     */
    private function customInclusion(Stringable $contents, Stringable $current_file_path): Stringable
    {
        return Str::of((new Regex)->replace("/%- include (.*) -%/", function (MatchResult $result) use ($current_file_path) {
            $path = Str::of($result->group(1))
                ->replace("__FILE__", "\"" . $current_file_path . "\"")
                ->replace("__DIR__", "\"" . Str::of($current_file_path)->beforeLast(DIRECTORY_SEPARATOR) . "\"")
                ->replace("__NAMESPACE__", null)
                ->replace("\\", "\\\\");

            $path = realpath(eval("return " . $path . ";"));

            if (! file_exists($path)) {
                throw new FileNotExistsException;
            }

            return file_get_contents($path);
        }, $contents->__toString())->result());
    }

    /**
     * Merger constructor.
     * @param Alo $alo
     */
    public function __construct(Alo $alo)
    {
        $this->alo = $alo;
        $this->contents = $this->alo->contents;
    }

    /**
     * @return Merger
     * @throws FileNotExistsException
     */
    public function merge(): Merger
    {
        $this->contents = $this->loadFileContents($this->alo->main_file);
        return $this;
    }
}
