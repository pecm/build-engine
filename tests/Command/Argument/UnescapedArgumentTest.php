<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command\Argument;

use BuildEngine\Command\Argument\ArgumentInterface;
use BuildEngine\Command\Argument\UnescapedArgument;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UnescapedArgumentTest extends TestCase {
  public function testEmptyArgument(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$value must not be empty');

    new UnescapedArgument('');
  }

  public function testPipeTo(): void {
    $arg = UnescapedArgument::pipeTo();

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame('|', $arg->getValue());
  }

  public function testRedirectTo(): void {
    $arg = UnescapedArgument::redirectTo();

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame('>', $arg->getValue());
  }

  public function testAppendTo(): void {
    $arg = UnescapedArgument::appendTo();

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame('>>', $arg->getValue());
  }

  public function testCustomArgument(): void {
    $arg = new UnescapedArgument('ls');

    $this->assertInstanceOf(ArgumentInterface::class, $arg);
    $this->assertSame('ls', $arg->getValue());
  }
}
