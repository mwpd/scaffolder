<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Exception\ScaffolderException;
use MWPD\Scaffolder\Exception\UserAbortion;
use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\Prompt;
use MWPD\Scaffolder\UserInput;

abstract class Choice implements Prompt
{

    /**
     * Choice to present to the user.
     *
     * @var string
     */
    protected $choice;

    /**
     * Index of the default choice to preselect.
     *
     * @var int
     */
    protected $defaultValue;

    /**
     * Instantiate a ChoicePrompt object.
     *
     * @param string $choice       Choice to ask.
     * @param int    $defaultValue Optional. Index of the default choice to preselect. Defaults to 0.
     */
    public function __construct(string $choice, int $defaultValue = 0)
    {
        $this->choice       = $choice;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Prompt the user and retrieve the response.
     *
     * @param UserInput $userInput User input implementation to use.
     * @param Logger    $logger    Logger implementation to use.
     * @return string Validated response from the user.
     * @throws ScaffolderException If the prompt could not be successfully executed.
     */
    public function execute(UserInput $userInput, Logger $logger): string
    {
        $answer     = $this->getDefaultValue();
        $isAnswered = false;
        $options    = $this->getOptions();

        do {
            try {
                $answer     = $userInput->choice($this->getChoice(), $options, $this->getDefaultValue());
                $isAnswered = true;
            } catch (ScaffolderException $exception) {
                if ($exception instanceof UserAbortion) {
                    throw $exception;
                }
                $logger->warning($exception->getMessage());
            }
        } while (! $isAnswered);

        return array_keys($options)[$answer];
    }

    /**
     * Get the choice to present to the user.
     *
     * @return string Choice to present to the user.
     */
    protected function getChoice(): string
    {
        return $this->choice;
    }

    /**
     * Get the index of the default choice to preselect.
     *
     * @return int Index of the default choice to preselect.
     */
    protected function getDefaultValue(): int
    {
        return $this->defaultValue;
    }

    /**
     * Get the available options between which the user can choose.
     *
     * @return array Array of available options between which the user can choose.
     */
    abstract protected function getOptions(): array;
}
