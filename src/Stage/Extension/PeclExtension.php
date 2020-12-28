<?php
declare(strict_types = 1);

namespace BuildEngine\Stage\Extension;

use BuildEngine\Command\CommandBuilder;
use BuildEngine\Command\CommandCollection;
use InvalidArgumentException;

final class PeclExtension {
  private CommandBuilder $commandBuilder;
  private string $extensionName;

  /**
   * @param array<string, string> $input
   */
  public static function fromArray(array $input): self {
    assert(
      isset($input['extensionName']),
      new InvalidArgumentException('$input must have an "extensionName" key')
    );

    assert(
      $input['extensionName'] !== '',
      new InvalidArgumentException('$input[\'extensionName\'] must not be empty')
    );

    return new self(
      new CommandBuilder(),
      $input['extensionName']
    );
  }

  public function __construct(
    CommandBuilder $commandBuilder,
    string $extensionName
  ) {
    $this->commandBuilder = $commandBuilder;

    $this->extensionName = $extensionName;
  }

  public function getExtensionName(): string {
    return $this->extensionName;
  }

  public function build(): CommandCollection {
    $cmdCollection = new CommandCollection();

    $cmdCollection->add(
      $this->commandBuilder
        ->setCommand('pecl')
        ->addArgument('install')
        ->addArgument($this->getExtensionName())
        ->build()
    );

    $this->commandBuilder->reset();
    $cmdCollection->add(
      $this->commandBuilder
        ->setCommand('pecl')
        ->addArgument('run-tests')
        ->addArgument($this->getExtensionName())
        ->build()
    );

    return $cmdCollection;
  }
}
