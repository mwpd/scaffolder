<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\TemplateEngine;

use MWPD\Scaffolder\Context;
use MWPD\Scaffolder\Exception\UnsupportedTemplate;
use MWPD\Scaffolder\TemplateEngine;
use PHPUnit\Framework\TestCase;

/**
 * Class AggregateTemplateEngineTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\TemplateEngine\AggregateTemplateEngine
 */
final class AggregateTemplateEngineTest extends TestCase
{

    public function testItCanBeInstantiated(): void
    {
        $aggregateEngine = new AggregateTemplateEngine();
        $this->assertInstanceOf(TemplateEngine::class, $aggregateEngine);
    }

    public function testItAcceptsAnInjectedAggregateInstance(): void
    {
        $mockEngine      = $this->createMock(TemplateEngine::class);
        $aggregateEngine = new AggregateTemplateEngine($mockEngine);
        $this->assertInstanceOf(TemplateEngine::class, $aggregateEngine);
    }

    public function testItCanProcessWithInjectedEngine(): void
    {
        $mockEngine = $this->createMock(TemplateEngine::class);

        $mockEngine->method('canProcess')->willReturnCallback(
            static function ($input, $context) {
                return $context->has(Context::TYPE) && $context->get(Context::TYPE) === 'mock-engine';
            }
        );

        $mockEngine->method('process')->willReturnCallback(
            static function ($input, $context) {
                return str_replace('%%name%%', $context->get('name'), $input);
            }
        );

        $aggregateEngine = new AggregateTemplateEngine($mockEngine);

        $this->assertFalse($aggregateEngine->canProcess('input', new Context()));
        $this->assertFalse($aggregateEngine->canProcess('input', new Context([Context::TYPE => 'other-engine'])));
        $this->assertTrue($aggregateEngine->canProcess('input', new Context([Context::TYPE => 'mock-engine'])));
        $this->assertEquals(
            'Hello World!',
            $aggregateEngine->process(
                'Hello %%name%%!',
                new Context([Context::TYPE => 'mock-engine', 'name' => 'World'])
            )
        );
    }

    public function testItCanProcessWithMultipleInjectedEngine(): void
    {
        $mockEngineA = $this->createMock(TemplateEngine::class);

        $mockEngineA->method('canProcess')->willReturnCallback(
            static function ($input, $context) {
                return $context->has(Context::TYPE) && $context->get(Context::TYPE) === 'mock-engine-a';
            }
        );

        $mockEngineA->method('process')->willReturnCallback(
            static function ($input, $context) {
                return str_replace('%%name%%', 'from mock engine A', $input);
            }
        );

        $mockEngineB = $this->createMock(TemplateEngine::class);

        $mockEngineB->method('canProcess')->willReturnCallback(
            static function ($input, $context) {
                return $context->has(Context::TYPE) && $context->get(Context::TYPE) === 'mock-engine-b';
            }
        );

        $mockEngineB->method('process')->willReturnCallback(
            static function ($input, $context) {
                return str_replace('%%name%%', 'from mock engine B', $input);
            }
        );

        $aggregateEngine = new AggregateTemplateEngine($mockEngineA, $mockEngineB);

        $this->assertFalse($aggregateEngine->canProcess('input', new Context()));
        $this->assertFalse($aggregateEngine->canProcess('input', new Context([Context::TYPE => 'other-engine'])));
        $this->assertTrue($aggregateEngine->canProcess('input', new Context([Context::TYPE => 'mock-engine-a'])));
        $this->assertTrue($aggregateEngine->canProcess('input', new Context([Context::TYPE => 'mock-engine-b'])));
        $this->assertEquals(
            'Hello from mock engine A!',
            $aggregateEngine->process(
                'Hello %%name%%!',
                new Context([Context::TYPE => 'mock-engine-a', 'name' => 'World'])
            )
        );
        $this->assertEquals(
            'Hello from mock engine B!',
            $aggregateEngine->process(
                'Hello %%name%%!',
                new Context([Context::TYPE => 'mock-engine-b', 'name' => 'World'])
            )
        );
    }

    public function testItThrowsOnUnsupportedTemplates(): void
    {
        $templateEngine = new AggregateTemplateEngine();
        $this->expectException(UnsupportedTemplate::class);
        $templateEngine->process('Hello {{name}}!', new Context(['name' => 'World']));
    }
}
