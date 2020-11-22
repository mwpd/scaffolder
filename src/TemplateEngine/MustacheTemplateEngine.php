<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\TemplateEngine;

use Mustache_Engine;
use MWPD\Scaffolder\Context;
use MWPD\Scaffolder\Exception\UnsupportedTemplate;
use MWPD\Scaffolder\TemplateEngine;
use Webmozart\PathUtil\Path;

final class MustacheTemplateEngine implements TemplateEngine
{

    /**
     * Template engine type value.
     *
     * @var string
     */
    public const TYPE = 'mustache';

    /**
     * Mustache engine instance to use.
     *
     * @var Mustache_Engine
     */
    private $mustache;

    /**
     * Instantiate a MustacheTemplateEngine object.
     *
     * @param Mustache_Engine $mustache Optional. Mustache engine instance to
     *                                  use.
     */
    public function __construct(Mustache_Engine $mustache = null)
    {
        $this->mustache = $mustache ?? new Mustache_Engine();
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
        if ($context->has(Context::TYPE)
            && $context->get(Context::TYPE) === self::TYPE) {
            return true;
        }

        if ($context->has(Context::FILEPATH)) {
            $filepath = $context->get(Context::FILEPATH);
            if (Path::hasExtension($filepath, 'mustache')
                || false !== stripos(Path::getFilename($filepath), '.mustache.')) {
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
        if (! $this->canProcess($input, $context)) {
            throw UnsupportedTemplate::fromProcessable($input, $context);
        }

        return $this->mustache->render($input, $context->toArray());
    }
}
