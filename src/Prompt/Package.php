<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Exception;

final class Package extends Question
{

    /**
     * Regular expression to validate a package name.
     *
     * @todo improve regex.
     */
    private const PACKAGE_NAME_REGEX_PATTERN = '/^[0-9a-zA-Z-_]+\/[0-9a-zA-Z-_]+$/';

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
        // @todo Transform random strings into a valid package name.
        return $defaultValue;
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
        if (! preg_match(self::PACKAGE_NAME_REGEX_PATTERN, $input)) {
            throw new Exception\FailedToValidate('The package name must be in the form "<vendor>/<package>".');
        }

        return $input;
    }
}
