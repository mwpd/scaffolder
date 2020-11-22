<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\Exception;

use InvalidArgumentException;
use MWPD\Scaffolder\Context;

final class UnsupportedTemplate extends InvalidArgumentException implements ScaffolderException
{

    public static function fromProcessable(string $input, Context $context)
    {
        $message = "Provided template could not be processed as it is not supported by any of the known engines.";

        if ($context->has(Context::FILEPATH)) {
            $message .= "\nProvided template file path: {$context->get(Context::FILEPATH)}";
        }

        if ($context->has(Context::TYPE)) {
            $message .= "\nProvided template type: {$context->get(Context::TYPE)}";
        }

        if (! empty($input)) {
            if (mb_strlen($input) > 300) {
                $input = mb_substr($input, 0, 300) . '...';
            }
            $message .= "\nTemplate content:\n" . $input;
        }

        return new self($message);
    }
}
