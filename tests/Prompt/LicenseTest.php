<?php
declare(strict_types=1);

namespace MWPD\Scaffolder\Prompt;

use MWPD\Scaffolder\Logger;
use MWPD\Scaffolder\UserInput;
use PHPUnit\Framework\TestCase;

/**
 * Class LicenseTest.
 *
 * @package MWPD\Scaffolder
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 *
 * @covers  MWPD\Scaffolder\Prompt\License
 */
final class LicenseTest extends TestCase
{

    public function testItCanBeInstantiated()
    {
        $license = new License('message');
        $this->assertInstanceOf(License::class, $license);
    }

    public function testItCanAcceptADefaultValue()
    {
        $license = new License('message', 1);
        $this->assertInstanceOf(License::class, $license);
    }

    public function testItCanBeExecuted()
    {
        $userInput = $this->createMock(UserInput::class);
        $userInput->method('choice')->willReturn(2);
        $logger  = $this->createMock(Logger::class);
        $license = new License('message', 1);
        $answer  = $license->execute($userInput, $logger);
        $this->assertEquals('apache', $answer);
    }
}
