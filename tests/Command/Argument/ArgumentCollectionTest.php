<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command\Argument;

use BuildEngine\Command\Argument\ArgumentInterface;
use BuildEngine\Command\Argument\EscapedArgument;
use BuildEngine\Command\Argument\UnescapedArgument;
use BuildEngine\Command\Argument\ArgumentCollection;
use PHPUnit\Framework\TestCase;

class ArgumentCollectionTest extends TestCase {
  public function testGetType(): void {
    $col = new ArgumentCollection();

    $this->assertSame(ArgumentInterface::class, $col->getType());
  }

  public function testToArray(): void {
    $col = new ArgumentCollection();

    $this->assertTrue($col->add(new EscapedArgument('x')));
    $this->assertTrue($col->add(UnescapedArgument::pipeTo()));

    $this->assertSame(["'x'", '|'], $col->toArray());
  }
}
