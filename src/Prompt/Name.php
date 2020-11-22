<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Exception;

final class Name extends Question
{

    /**
     * Minimum character length of a name.
     */
    public const MINIMUM_LENGTH = 2;

    /**
     * Sanitize a default value.
     *
     * This is used to adapt the format of a prior response from the user so that it fits as a new default value.
     *
     * @param string $defaultValue Default value to sanitize.
     * @return string Sanitized default value.
     */
    protected function sanitize(string $defaultValue): string
    {
        return trim($defaultValue);
    }

    /**
     * Validate and return the user input.
     *
     * @param string $input User input to validate.
     * @return string Validated user input.
     * @throws Exception\ScaffolderException If the user input could not be successfully validated.
     */
    protected function validate(string $input): string
    {
        if (mb_strlen($input) < self::MINIMUM_LENGTH) {
            throw new Exception\FailedToValidate(
                sprintf(
                    'The name must be a string of at least %d characters in length.',
                    self::MINIMUM_LENGTH
                )
            );
        }

        return trim($input);
    }
}
