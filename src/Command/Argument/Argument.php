<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

interface Argument {
  public function getValue(): string;
}
