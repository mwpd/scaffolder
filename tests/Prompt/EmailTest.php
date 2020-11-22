<?php

declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\UserInput;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\Prompt\Email
 */
final class EmailTest extends TestCase
{

    public function testItCanBeInstantiated()
    {
        $email = new Email('message');
        $this->assertInstanceOf(Email::class, $email);
    }

    public function testItCanAcceptADefaultValue()
    {
        $email = new Email('message', 'default value');
        $this->assertInstanceOf(Email::class, $email);
    }

    public function testItCanBeExecuted()
    {
        $userInput = $this->createMock(UserInput::class);
        $userInput->method('question')->willReturn('valid.email@example.com');
        $logger = $this->createMock(Logger::class);
        $email  = new Email('message', 'default value');
        $answer = $email->execute($userInput, $logger);
        $this->assertEquals('valid.email@example.com', $answer);
    }
}
