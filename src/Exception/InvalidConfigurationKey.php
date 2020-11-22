<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Exception;

use InvalidArgumentException;

final class InvalidConfigurationKey extends InvalidArgumentException implements ScaffolderException
{

    public static function fromKey(string $key)
    {
        $message = "The configuration does not contain data for the key '{$key}'.";

        return new self($message);
    }
}
