<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Exception;

use InvalidArgumentException;

final class InvalidDataStoreKey extends InvalidArgumentException implements ScaffolderException
{

    public static function fromKey(string $key)
    {
        $message = "The data store does not contain data for the key '{$key}'.";

        return new self($message);
    }
}
