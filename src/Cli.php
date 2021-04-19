<?php


namespace IsaEken\Alo;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

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

        if (! $this->watch) {
            $this->alo->run();
        }
        else {
            $tempDirectory = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "tmp";
            if (! is_dir($tempDirectory)) {
                mkdir($tempDirectory);
            }

            $hashFile = $tempDirectory . DIRECTORY_SEPARATOR . "last_directory_hash";
            if (! file_exists($hashFile)) {
                touch($hashFile);
            }

            print "\r\n[ Watching File Changes For Auto Compile ]\r\n";

            while (true) {
                $hash = Helpers::hashDirectory($this->alo->project_path);
                if (file_get_contents($hashFile) != $hash) {
                    print "Compiling...\r\n";

                    try {
                        $this->alo->run();
                    }
                    catch (Exception $exception) {
                        print "[ ERR ] " . $exception->getCode() . ": " .$exception->getMessage() . "\r\n";
                    }

                    file_put_contents($hashFile, $hash);
                }

                sleep(0.5);
            }
        }

        return $this;
    }

    public function help(): void
    {
        print "\r\n";
        print "[All In One Compiler]\r\n";
        print "aol.php </path/of/your/project> <index.php> <out.php> [--auto-merge-requires]";
        print "\r\n\r\n";
    }
}
