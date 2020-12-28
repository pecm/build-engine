<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command;

use BuildEngine\Command\Argument\EscapedArgument;
use BuildEngine\Command\Argument\ArgumentCollection;
use BuildEngine\Command\Command;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase {
  public function testEmptyCommand(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$command must not be empty');

    new Command('');
  }

  public function testEmptyArgument(): void {
    $cmd = new Command('ls');

    $this->assertSame('ls', $cmd->getCommand());
    $this->assertFalse($cmd->hasArguments());
    $this->assertSame('ls', $cmd->toString());
  }

  public function testWithArguments(): void {
    $col = new ArgumentCollection();
    $col->add(new EscapedArgument('-lah'));
    $cmd = new Command('ls', $col);

    $this->assertSame('ls', $cmd->getCommand());
    $this->assertTrue($cmd->hasArguments());
    $this->assertSame("ls '-lah'", $cmd->toString());
  }
}
