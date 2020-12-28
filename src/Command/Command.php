<?php
declare(strict_types = 1);

namespace BuildEngine\Command;

use BuildEngine\Command\Argument\ArgumentCollection;
use InvalidArgumentException;

final class Command {
  private string $command;
  private ArgumentCollection $arguments;

  public function __construct(string $command, ArgumentCollection $arguments = null) {
    $command = trim($command);

    assert(
      $command !== '',
      new InvalidArgumentException('$command must not be empty')
    );

    $this->command = $command;
    if ($arguments === null) {
      $arguments = new ArgumentCollection();
    }

    $this->arguments = $arguments;
  }

  public function getCommand(): string {
    return $this->command;
  }

  public function hasArguments(): bool {
    return $this->arguments->isEmpty() === false;
  }

  public function getArguments(): ArgumentCollection {
    return $this->arguments;
  }

  public function toString(): string {
    if ($this->arguments->isEmpty()) {
      return $this->command;
    }

    return sprintf(
      '%s %s',
      $this->command,
      implode(
        ' ',
        $this->arguments->toArray()
      )
    );
  }
}
