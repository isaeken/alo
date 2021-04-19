<?php


namespace IsaEken\Alo;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use IsaEken\Alo\Exceptions\DirectoryNotExistsException;

/**
 * Class Cli
 * @package IsaEken\Alo
 */
class Cli
{
    /**
     * @var Alo $alo
     */
    public Alo $alo;

    /**
     * @var Collection $arguments
     */
    public Collection $arguments;

    /**
     * @var Collection $options
     */
    public Collection $options;

    /**
     * @var bool $watch
     */
    public bool $watch = false;

    /**
     * @param Alo $alo
     * @param array|Collection $argv
     * @return Cli
     * @throws Exceptions\DirectoryNotExistsException
     * @throws Exceptions\FileNotExistsException
     */
    public function route(Alo $alo, array|Collection $argv): Cli
    {
        $this->alo = $alo;

        $argv = Helpers::formatArgv($argv);
        $this->arguments = $argv->arguments;
        $this->options = $argv->options;

        foreach ($this->options as $option => $value) {
            switch ($option) {
                case "watch":
                    $this->watch = $value === null || ($value instanceof Stringable && $value->length() < 1) || $value;
                    break;

                case "help":
                    $this->help();
                    exit(0);
            }
        }

        if ($this->arguments->count() < 3) {
            $this->help();
            exit(1);
        }

        $this->alo->project_path = Str::of($this->arguments[0]);
        $this->alo->main_file = Str::of($this->arguments[1]);
        $this->alo->output = Str::of($this->arguments[2]);

        if (! is_dir(realpath($this->alo->project_path->__toString()))) {
            throw new DirectoryNotExistsException;
        }
        else {
            $this->alo->project_path = Str::of(realpath($this->alo->project_path->__toString()));
        }

        if (! $this->watch) {
            $this->alo->run();
        }
        else {
            $cli = $this;
            $watcher = new Watcher;

            $watcher->directory = $this->alo->project_path;
            $watcher->sleep = 1;
            $watcher->callback = function () use ($cli) {
                if (Helpers::is_cli()) {
                    Helpers::output("Compiling...");
                }

                $cli->alo->run();
            };

            $watcher->run();
        }

        return $this;
    }

    /**
     * Write help text to CLI
     */
    public function help(): void
    {
        print "\033[32m[ All In One Compiler ]\033[37m\r\n";
        Helpers::output("Usage:");
        Helpers::output("/bin/php alo.php /path/of/your/project your_index_file.php output_file.php [--watch=true|false]");
    }
}
