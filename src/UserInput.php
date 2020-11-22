<?php
declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\ScaffolderException;
use MWPD\Scaffolder\Exception\UserAbortion;

interface UserInput
{

    /**
     * Ask the user a question.
     *
     * @param string $question     Question to ask.
     * @param string $defaultValue Optional. Default value to use.
     * @return string Response from the user.
     * @throws UserAbortion If the user aborted answering to the question.
     * @throws ScaffolderException If an error occurred other than user abortion.
     */
    public function question(string $question, string $defaultValue = ''): string;

    /**
     * Let the user make a choice.
     *
     * @param string $choice       Choice for the user to make.
     * @param array  $options      Array of options between which the user can choose.
     * @param int    $defaultValue Optional. Index of the default option to preselect. Defaults to 0.
     * @return int Index of the option the user chose.
     * @throws UserAbortion If the user aborted making a choice.
     * @throws ScaffolderException If an error occurred other than user abortion.
     */
    public function choice(string $choice, array $options, int $defaultValue = 0): int;
}
