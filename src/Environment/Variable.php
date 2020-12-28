<?php
declare(strict_types = 1);

namespace BuildEngine\Environment;

use InvalidArgumentException;

final class Variable {
  private string $name;
  private string $value;

  public function __construct(string $name, string $value = '') {
    $name = trim($name);

    assert(
      $name !== '',
      new InvalidArgumentException('$name must not be empty')
    );

    $this->name  = $name;
    $this->value = $value;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getValue(): string {
    return $this->value;
  }

  public function toString(): string {
    return sprintf(
      '%s=%s',
      $this->name,
      $this->value
    );
  }
}
