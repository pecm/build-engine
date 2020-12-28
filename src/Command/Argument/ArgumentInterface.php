<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

interface ArgumentInterface {
  public function getValue(): string;
}
