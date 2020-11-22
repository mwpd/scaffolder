<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\UserInput;
use PHPUnit\Framework\TestCase;

/**
 * Class PackageTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\Prompt\Package
 */
final class PackageTest extends TestCase
{

    public function testItCanBeInstantiated()
    {
        $package = new Package('message');
        $this->assertInstanceOf(Package::class, $package);
    }

    public function testItCanAcceptADefaultValue()
    {
        $package = new Package('message', 'default value');
        $this->assertInstanceOf(Package::class, $package);
    }

    public function testItCanBeExecuted()
    {
        $userInput = $this->createMock(UserInput::class);
        $userInput->method('question')->willReturn('mwpd/scaffolder');
        $logger  = $this->createMock(Logger::class);
        $package = new Package('message', 'default value');
        $answer  = $package->execute($userInput, $logger);
        $this->assertEquals('mwpd/scaffolder', $answer);
    }
}
