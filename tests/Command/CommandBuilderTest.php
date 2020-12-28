<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command;

use BuildEngine\Command\Argument\EscapedArgument;
use BuildEngine\Command\Argument\ArgumentCollection;
use BuildEngine\Command\Command;
use BuildEngine\Command\CommandBuilder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CommandBuilderTest extends TestCase {
  public function testEmptyCommandImplicit(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$command must not be empty');

    (new CommandBuilder())
      ->build();
  }

  public function testEmptyCommandExplicit(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$command must not be empty');

    (new CommandBuilder())
      ->setCommand('')
      ->build();
  }

  public function testEmptyArgument(): void {
    $cmd = (new CommandBuilder())
      ->setCommand('ls')
      ->build();

    $this->assertSame('ls', $cmd->getCommand());
    $this->assertFalse($cmd->hasArguments());
    $this->assertSame('ls', $cmd->toString());
  }

  public function testWithArguments(): void {
    $cmd = (new CommandBuilder())
      ->setCommand('ls')
      ->addArgument('-lah')
      ->build();

    $this->assertSame('ls', $cmd->getCommand());
    $this->assertTrue($cmd->hasArguments());
    $this->assertSame("ls '-lah'", $cmd->toString());
  }

  public function testWithPipeTo(): void {
    $cmd = (new CommandBuilder())
      ->setCommand('cat')
      ->addArgument('/tmp/file')
      ->pipeTo(
        (new CommandBuilder())
          ->setCommand('wc')
          ->addArgument('-l')
          ->build()
      )
      ->build();

    $this->assertSame('cat', $cmd->getCommand());
    $this->assertTrue($cmd->hasArguments());
    $this->assertSame("cat '/tmp/file' | wc '-l'", $cmd->toString());
  }

  public function testWithRedirectTo(): void {
    $cmd = (new CommandBuilder())
      ->setCommand('cat')
      ->addArgument('/tmp/file')
      ->redirectTo('/dev/null')
      ->build();

    $this->assertSame('cat', $cmd->getCommand());
    $this->assertTrue($cmd->hasArguments());
    $this->assertSame("cat '/tmp/file' > '/dev/null'", $cmd->toString());
  }

  public function testWithAppendTo(): void {
    $cmd = (new CommandBuilder())
      ->setCommand('cat')
      ->addArgument('/tmp/file')
      ->appendTo('/dev/null')
      ->build();

    $this->assertSame('cat', $cmd->getCommand());
    $this->assertTrue($cmd->hasArguments());
    $this->assertSame("cat '/tmp/file' >> '/dev/null'", $cmd->toString());
  }

  public function testReset(): void {
    $cmdBuilder = new CommandBuilder();
    $cmd1 = $cmdBuilder
      ->setCommand('ls')
      ->addArgument('-lah')
      ->build();

    $this->assertSame('ls', $cmd1->getCommand());
    $this->assertTrue($cmd1->hasArguments());
    $this->assertSame("ls '-lah'", $cmd1->toString());

    // without calling reset(), $cmd2 "inherits" $cmd1's arguments
    $cmd2 = $cmdBuilder
      ->setCommand('echo')
      ->build();

    $this->assertSame('echo', $cmd2->getCommand());
    $this->assertTrue($cmd2->hasArguments());
    $this->assertSame("echo '-lah'", $cmd2->toString());

    // this forces a clean builder
    $cmdBuilder->reset();
    $cmd3 = $cmdBuilder
      ->setCommand('date')
      ->build();

    $this->assertSame('date', $cmd3->getCommand());
    $this->assertFalse($cmd3->hasArguments());
    $this->assertSame('date', $cmd3->toString());
  }
}
