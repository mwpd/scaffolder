<?php
declare(strict_types=1);

namespace MWPD\Scaffolder;

interface Logger
{

    /**
     * Terminate a successful operation with positive feedback.
     *
     * @param string $message Message summarizing the successful operation.
     * @param int    $code    Optional. Code to use for the success message. Defaults to 0.
     */
    public function success(string $message, int $code = 0): void;

    /**
     * Terminate a failed operation with negative feedback.
     *
     * @param string $message Message describing the reason of the failure.
     * @param int    $code    Optional. Code to use for the error message. Defaults to 1.
     */
    public function error(string $message, int $code = 1): void;

    /**
     * Log an informational message.
     *
     * @param string $message Informational message to log.
     */
    public function info(string $message): void;

    /**
     * Log a warning message.
     *
     * @param string $message Warning message to log.
     */
    public function warning(string $message): void;

    /**
     * Log a debugging message.
     *
     * @param string $message Debugging message to log.
     */
    public function debug(string $message): void;
}
