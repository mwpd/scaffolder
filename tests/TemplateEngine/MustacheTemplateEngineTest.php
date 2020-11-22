<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\TemplateEngine;

use Mustache_Engine;
use MWPD\Scaffolder\Context;
use MWPD\Scaffolder\Exception\UnsupportedTemplate;
use MWPD\Scaffolder\TemplateEngine;
use PHPUnit\Framework\TestCase;

/**
 * Class MustacheTemplateEngineTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\TemplateEngine\MustacheTemplateEngine
 */
final class MustacheTemplateEngineTest extends TestCase
{

    public function testItCanBeInstantiated(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->assertInstanceOf(TemplateEngine::class, $templateEngine);
    }

    public function testItAcceptsAnInjectedMustacheInstance(): void
    {
        $mustacheInstance = $this->createMock(Mustache_Engine::class);
        $templateEngine   = new MustacheTemplateEngine($mustacheInstance);
        $this->assertInstanceOf(TemplateEngine::class, $templateEngine);
    }

    public function testItCanDetectType(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->assertTrue(
            $templateEngine->canProcess('input', new Context([Context::TYPE => MustacheTemplateEngine::TYPE]))
        );
    }

    public function testItCanDetectFilepath(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->assertTrue(
            $templateEngine->canProcess('input', new Context([Context::FILEPATH => '/some/file.mustache']))
        );
        $this->assertTrue(
            $templateEngine->canProcess('input', new Context([Context::FILEPATH => '/some/file.mustache.html']))
        );
    }

    public function testItDetectsUnsupportedTemplates(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->assertFalse(
            $templateEngine->canProcess('input', new Context())
        );
    }

    public function testItThrowsOnUnsupportedTemplates(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->expectException(UnsupportedTemplate::class);
        $templateEngine->process('Hello {{name}}!', new Context(['name' => 'World']));
    }

    public function testItCanProcessWithDefaultMustacheInstance(): void
    {
        $templateEngine = new MustacheTemplateEngine();
        $this->assertEquals(
            'Hello World!',
            $templateEngine->process(
                'Hello {{name}}!',
                new Context([Context::TYPE => MustacheTemplateEngine::TYPE, 'name' => 'World'])
            )
        );
    }

    public function testItCanProcessWithInjectedMustacheInstance(): void
    {
        $mustacheInstance = $this->createMock(Mustache_Engine::class);
        $mustacheInstance->method('render')->willReturn('Hello Mocked World!');
        $templateEngine = new MustacheTemplateEngine($mustacheInstance);
        $this->assertEquals(
            'Hello Mocked World!',
            $templateEngine->process(
                'Hello {{name}}!',
                new Context([Context::TYPE => MustacheTemplateEngine::TYPE, 'name' => 'World'])
            )
        );
    }
}
