<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Command\Command;

use BuildEngine\Command\Command;
use BuildEngine\Command\CommandCollection;
use PHPUnit\Framework\TestCase;

class CommandCollectionTest extends TestCase {
  public function testGetType(): void {
    $col = new CommandCollection();

    $this->assertSame(Command::class, $col->getType());
  }
}
