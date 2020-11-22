<?php
declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\ScaffolderException;

interface Prompt
{

    /**
     * Prompt the user and retrieve the response.
     *
     * @param UserInput $userInput User input implementation to use.
     * @param Logger    $logger    Logger implementation to use.
     * @return string Validated response from the user.
     * @throws ScaffolderException If the prompt could not be successfully executed.
     */
    public function execute(UserInput $userInput, Logger $logger): string;
}
