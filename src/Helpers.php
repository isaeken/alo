<?php


namespace IsaEken\Alo;


use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * Class Helpers
 * @package IsaEken\Alo
 */
class Helpers
{
    /**
     * Generate hash a directory.
     *
     * @param string|Stringable $directory
     * @return Stringable
     */
    public static function hashDirectory(string|Stringable $directory): Stringable
    {
        $directory = !($directory instanceof Stringable) ? Str::of($directory) : $directory;

        if (! is_dir($directory)) {
            return new Stringable;
        }

        $hash = collect();
        $dir = dir($directory->__toString());

        while (false !== ($entry = $dir->read())) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($directory . DIRECTORY_SEPARATOR . $entry)) {
                    $hash->add(self::hashDirectory($directory . DIRECTORY_SEPARATOR . $entry));
                }
                else {
                    $hash->add(md5_file($directory . DIRECTORY_SEPARATOR . $entry));
                }
            }
        }

        $dir->close();
        return Str::of(md5(implode("", $hash->toArray())));
    }

    /**
     * Print message with hour for CLI.
     *
     * @param string|Stringable $message
     * @return Stringable
     */
    public static function output(string|Stringable $message): Stringable
    {
        $message = Str::of("\033[32m[ " . date("H:i:s") . " ] \033[37m")->append($message);
        print $message->__toString() . "\r\n";
        return $message;
    }

    /**
     * Group argv to arguments and options.
     *
     * @param array|Collection $argv
     * @return object
     */
    public static function formatArgv(array|Collection $argv): object
    {
        $arguments = $argv instanceof Collection ? $argv : collect($argv);
        $options = new Collection;
        $remove_arguments = collect();

        foreach ($arguments as $key => $argument) {
            if (Str::of($argument)->startsWith("--")) {
                $options->add($argument);
                $remove_arguments->add($key);
            }
        }

        $args = $arguments->filter(fn ($argument, $key) => !in_array($key, $remove_arguments->toArray()));
        $opts = new Collection;

        foreach ($options as $option) {
            $option = Str::of($option)->substr(2);
            $key = null;

            if ($option->contains("=")) {
                $key = $option->before("=");
                $option = $option->after("=");
            }

            if ($key instanceof Stringable) {
                if ($option instanceof Stringable) {
                    $opt = $option->lower()->trim()->replace(" ", null);
                    if ($opt == "true") {
                        $option = true;
                    }
                    else if ($opt == "false") {
                        $option = false;
                    }
                }

                $opts->put($key->__toString(), $option);
            }
            else if ($option instanceof Stringable) {
                $opts->put($option->__toString(), null);
            }
            else {
                $opts->add($option);
            }
        }

        return (object) [
            "arguments" => $args,
            "options" => $opts,
        ];
    }

    /**
     * Check script is running in CLI.
     *
     * @return bool
     */
    public static function is_cli(): bool
    {
        return php_sapi_name() == "cli";
    }
}
