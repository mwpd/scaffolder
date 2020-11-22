<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Exception;

use InvalidArgumentException;

final class InvalidManifestFile extends InvalidArgumentException implements ScaffolderException
{

    public static function fromFile(string $filepath)
    {
        $message = "The manifest file at {$filepath} cannot be read.";

        return new self($message);
    }
}
