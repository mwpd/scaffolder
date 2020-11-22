<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Exception;

final class Email extends Question
{

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
        return filter_var($defaultValue, FILTER_SANITIZE_EMAIL);
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
        if (filter_var($input, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception\FailedToValidate('The provided email is not valid.');
        }

        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }
}
