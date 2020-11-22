<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Exception;

use InvalidArgumentException;

final class InvalidContextKey extends InvalidArgumentException implements ScaffolderException
{

    public static function fromKey(string $key)
    {
        $message = "The context does not contain data for the key '{$key}'.";

        return new self($message);
    }
}
