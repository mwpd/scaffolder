<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Exception\ScaffolderException;
use MWPD\Scaffolder\Exception\UserAbortion;
use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\Prompt;
use MWPD\Scaffolder\UserInput;

abstract class Question implements Prompt
{

    /**
     * Question to ask the user.
     *
     * @var string
     */
    protected $question;

    /**
     * Default value to suggest.
     *
     * @var string
     */
    protected $defaultValue;

    /**
     * Instantiate a QuestionPrompt object.
     *
     * @param string $question     Question to ask.
     * @param string $defaultValue Optional. Default value to suggest.
     */
    public function __construct(string $question, $defaultValue = '')
    {
        $this->question     = $question;
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

        do {
            try {
                $answer     = $userInput->question($this->getQuestion(), $this->getDefaultValue());
                $answer     = $this->validate($answer);
                $isAnswered = true;
            } catch (ScaffolderException $exception) {
                if ($exception instanceof UserAbortion) {
                    throw $exception;
                }
                $logger->warning($exception->getMessage());
            }
        } while (! $isAnswered);

        return $answer;
    }

    /**
     * Get the question to ask.
     *
     * @return string Question to ask.
     */
    protected function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Get the default value to use.
     *
     * @return string Default value to use.
     */
    protected function getDefaultValue(): string
    {
        return $this->sanitize($this->defaultValue);
    }

    /**
     * Sanitize a default value.
     *
     * This is used to adapt the format of a prior response from the user so that it fits as a new default value.
     *
     * @param string $defaultValue Default value to sanitize.
     * @return string Sanitized default value.
     */
    abstract protected function sanitize(string $defaultValue): string;

    /**
     * Validate and return the user input.
     *
     * @param string $input User input to validate.
     * @return string Validated user input.
     * @throws ScaffolderException If the user input could not be successfully validated.
     */
    abstract protected function validate(string $input): string;
}
