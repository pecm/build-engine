<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Environment;

use BuildEngine\Environment\Variable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VariableTest extends TestCase {
  public function testEmptyName(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$name must not be empty');

    new Variable('');
  }

  public function testWithoutValue(): void {
    $var = new Variable('name');

    $this->assertSame('name', $var->getName());
    $this->assertSame('', $var->getValue());
    $this->assertSame('name=', $var->toString());
  }

  public function testWithValue(): void {
    $var = new Variable('name', 'value');

    $this->assertSame('name', $var->getName());
    $this->assertSame('value', $var->getValue());
    $this->assertSame('name=value', $var->toString());
  }
}
