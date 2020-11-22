<?php

declare(strict_types=1);

namespace MWPD\Scaffolder;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * Class ScaffoldingEngineTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\ScaffoldingEngine
 */
class ScaffoldingEngineTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * Set up test environment.
     */
    public function setUp(): void
    {
        $this->root = vfsStream::setup('targetDir');
    }

    public function testItCanBeInstantiated(): void
    {
        $scaffoldingEngine = new ScaffoldingEngine();
        $this->assertInstanceOf(ScaffoldingEngine::class, $scaffoldingEngine);
    }

    public function testItCanAcceptAnInjectedTemplateEngineInstance(): void
    {
        $templateEngine    = $this->createMock(TemplateEngine::class);
        $scaffoldingEngine = new ScaffoldingEngine($templateEngine);
        $this->assertInstanceOf(ScaffoldingEngine::class, $scaffoldingEngine);
    }

    public function testItCanScaffoldASingleFile(): void
    {
        $templateEngine = $this->createMock(TemplateEngine::class);
        $templateEngine->method('canProcess')->willReturn(true);
        $templateEngine->method('process')->willReturnCallback(
            static function ($input, $context) {
                foreach ($context->toArray() as $key => $value) {
                    $input = str_replace("{{{$key}}}", $value, $input);
                }

                return $input;
            }
        );
        $scaffoldingEngine = new ScaffoldingEngine($templateEngine, new DataStore(['name' => 'World']));
        $targetFilepath    = vfsStream::url('targetDir/test.txt');
        $this->assertTrue($scaffoldingEngine->scaffold(__DIR__ . '/fixtures/test.mustache.txt', $targetFilepath));
        $output = file_get_contents($this->root->getChild('test.txt')->url());
        $this->assertEquals('Hello World!', $output);
    }

    public function testItCanScaffoldASingleRemoteFile(): void
    {
        $templateEngine = $this->createMock(TemplateEngine::class);
        $templateEngine->method('canProcess')->willReturn(true);
        $templateEngine->method('process')->willReturnCallback(
            static function ($input, $context) {
                return $input;
            }
        );
        $scaffoldingEngine = new ScaffoldingEngine($templateEngine);
        $targetFilepath    = vfsStream::url('targetDir/test.txt');
        $this->assertTrue($scaffoldingEngine->scaffold('http://example.com/', $targetFilepath));
        $output = file_get_contents($this->root->getChild('test.txt')->url());
        $this->assertStringContainsString('<title>Example Domain</title>', $output);
    }
}
