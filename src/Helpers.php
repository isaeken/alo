<?php


namespace IsaEken\Alo;


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
     * @param string|Stringable $message
     * @return Stringable
     */
    public static function output(string|Stringable $message): Stringable
    {
        $message = Str::of("\033[32m[ " . date("H:i:s") . " ] \033[37m")->append($message);
        print $message->__toString() . "\r\n";
        return $message;
    }
}
