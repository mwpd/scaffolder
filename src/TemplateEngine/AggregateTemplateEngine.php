<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\TemplateEngine;

use MWPD\Scaffolder\Context;
use MWPD\Scaffolder\Exception\UnsupportedTemplate;
use MWPD\Scaffolder\TemplateEngine;

final class AggregateTemplateEngine implements TemplateEngine
{

    /**
     * Array of engines to aggregate.
     *
     * @var TemplateEngine[]
     */
    private $engines;

    /**
     * Instantiate a AggregateTemplateEngine object.
     *
     * @param TemplateEngine ...$engines Engines to aggregate.
     */
    public function __construct(TemplateEngine ...$engines)
    {
        $this->engines = $engines;
    }

    /**
     * Check whether the provided input can be processed.
     *
     * @param string  $input   Input to process.
     * @param Context $context Context information about the input to be processed.
     * @return bool Whether the provided input can be processed.
     */
    public function canProcess(string $input, Context $context): bool
    {
        foreach ($this->engines as $engine) {
            if ($engine->canProcess($input, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process the provided input and run all needed replacements.
     *
     * @param string  $input   Input to process.
     * @param Context $context Context information about the input to be processed.
     * @return string Processed output.
     * @throws UnsupportedTemplate If the provided template is not supported.
     */
    public function process(string $input, Context $context): string
    {
        foreach ($this->engines as $engine) {
            if ($engine->canProcess($input, $context)) {
                return $engine->process($input, $context);
            }
        }

        throw UnsupportedTemplate::fromProcessable($input, $context);
    }
}
