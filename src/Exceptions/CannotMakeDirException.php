<?php
declare(strict_types=1);

namespace Ueef\Paths\Exceptions;

use Exception;
use Throwable;

class CannotMakeDirException extends Exception
{
    public function __construct(string $dir, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("cannot make directory \"{$dir}\"", $code, $previous);
    }
}
