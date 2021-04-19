<?php


namespace IsaEken\Alo\Exceptions;


use RuntimeException;
use Throwable;

/**
 * Class DirectoryNotFoundException
 * @package IsaEken\Alo\Exceptions
 */
class DirectoryNotFoundException extends RuntimeException
{
    /**
     * DirectoryNotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string|null $path
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, string $path = null)
    {
        if ($message === null) {
            if ($path === null) {
                $message = "Directory could not be found.";
            }
            else {
                $message = sprintf("Directory \"%s\" could not be found.", $path);
            }
        }

        parent::__construct($message, $code, $previous);
    }
}
