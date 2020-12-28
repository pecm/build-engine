<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

final class EscapedArgument implements Argument {
  private string $value;

  public function __construct(string $value) {
    $this->value = escapeshellarg(trim($value));
  }

  public function getValue(): string {
    return $this->value;
  }
}
