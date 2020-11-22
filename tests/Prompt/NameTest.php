<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\UserInput;
use PHPUnit\Framework\TestCase;

/**
 * Class NameTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\Prompt\Name
 */
final class NameTest extends TestCase
{

    public function testItCanBeInstantiated()
    {
        $name = new Name('message');
        $this->assertInstanceOf(Name::class, $name);
    }

    public function testItCanAcceptADefaultValue()
    {
        $name = new Name('message', 'default value');
        $this->assertInstanceOf(Name::class, $name);
    }

    public function testItCanBeExecuted()
    {
        $userInput = $this->createMock(UserInput::class);
        $userInput->method('question')->willReturn('John Doe');
        $logger = $this->createMock(Logger::class);
        $name   = new Name('message', 'default value');
        $answer = $name->execute($userInput, $logger);
        $this->assertEquals('John Doe', $answer);
    }
}
