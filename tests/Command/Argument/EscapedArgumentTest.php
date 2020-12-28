<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command\Argument;

use BuildEngine\Command\Argument\ArgumentInterface;
use BuildEngine\Command\Argument\EscapedArgument;
use PHPUnit\Framework\TestCase;

class EscapedArgumentTest extends TestCase {
  public function testEmptyArgument(): void {
    $arg = new EscapedArgument('');

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame("''", $arg->getValue());
  }

  public function testStringArgument(): void {
    $arg = new EscapedArgument('abc');

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame("'abc'", $arg->getValue());
  }

  public function testStringArgumentWithSpaces(): void {
    $arg = new EscapedArgument(' abc ');

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame("'abc'", $arg->getValue());
  }
}
