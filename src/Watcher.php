<?php


namespace IsaEken\Alo;


use Closure;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * Class Watcher
 * @package IsaEken\Alo
 */
class Watcher
{
    /**
     * Hash storage file.
     *
     * @var Stringable $temp_file
     */
    private Stringable $temp_file;

    /**
     * Watcher watch directory.
     *
     * @var string|Stringable $directory
     */
    public string|Stringable $directory;

    /**
     * Watcher sleep time.
     *
     * @var float|int $sleep
     */
    public float|int $sleep = 1;

    /**
     * Watcher callback.
     *
     * @var Closure $callback
     */
    public Closure $callback;

    /**
     * Watcher state
     *
     * @var bool $active
     */
    private bool $active = true;

    /**
     * Watcher constructor.
     */
    public function __construct()
    {
        $temp_directory = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "tmp";
        if (! is_dir($temp_directory)) {
            mkdir($temp_directory);
        }

        $this->temp_file = Str::of($temp_directory . DIRECTORY_SEPARATOR . "watcher_hash");
        if (! file_exists($this->temp_file)) {
            touch($this->temp_file);
        }
    }

    /**
     * Run the watcher.
     */
    public function run(): void
    {
        while ($this->active) {
            $hash = Helpers::hashDirectory($this->directory);

            if (file_get_contents($this->temp_file) != $hash) {
                try {
                    $this->callback->call($this);
                }
                catch (Exception $exception) {

                }

                file_put_contents($this->temp_file, $hash);
            }

            sleep($this->sleep);
        }
    }

    /**
     * Stop the watcher.
     *
     * @return Watcher
     */
    public function stop(): Watcher
    {
        $this->active = false;
        return $this;
    }
}
