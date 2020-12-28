<?php
declare(strict_types = 1);

namespace BuildEngine\Command;

use BuildEngine\Command\Argument\ArgumentCollection;
use BuildEngine\Command\Argument\EscapedArgument;
use BuildEngine\Command\Argument\UnescapedArgument;

final class CommandBuilder {
  private string $command = '';
  private ArgumentCollection $arguments;

  public function __construct() {
    $this->arguments = new ArgumentCollection();
  }

  public function reset(): self {
    $this->command = '';
    $this->arguments = new ArgumentCollection();

    return $this;
  }

  public function setCommand(string $command): self {
    $this->command = trim($command);

    return $this;
  }

  public function addArgument(string $argument): self {
    $this->arguments->add(new EscapedArgument($argument));

    return $this;
  }

  public function pipeTo(Command $command): self {
    $this->arguments->add(UnescapedArgument::pipeTo());
    $this->arguments->add(new UnescapedArgument($command->getCommand()));
    foreach ($command->getArguments() as $argument) {
      $this->arguments->add($argument);
    }

    return $this;
  }

  public function redirectTo(string $file): self {
    $this->arguments->add(UnescapedArgument::redirectTo());
    $this->addArgument($file);

    return $this;
  }

  public function appendTo(string $file): self {
    $this->arguments->add(UnescapedArgument::appendTo());
    $this->addArgument($file);

    return $this;
  }

  public function build(): Command {
    return new Command($this->command, $this->arguments);
  }
}
