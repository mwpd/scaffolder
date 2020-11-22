<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use MWPD\Scaffolder\Exception\UnsupportedTemplate;

interface TemplateEngine
{

    /**
     * Check whether the provided input can be processed.
     *
     * @param string  $input   Input to process.
     * @param Context $context Context information about the input to be processed.
     * @return bool Whether the provided input can be processed.
     */
    public function canProcess(string $input, Context $context): bool;

    /**
     * Process the provided input and run all needed replacements.
     *
     * @param string  $input   Input to process.
     * @param Context $context Context information about the input to be processed.
     * @return string Processed output.
     * @throws UnsupportedTemplate If the provided template is not supported.
     */
    public function process(string $input, Context $context): string;
}
