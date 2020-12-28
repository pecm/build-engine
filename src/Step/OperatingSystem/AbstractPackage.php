<?php
declare(strict_types = 1);

namespace BuildEngine\Step\OperatingSystem;

use BuildEngine\Command\Command;
use BuildEngine\Command\CommandBuilder;
use BuildEngine\Step\StepInterface;
use InvalidArgumentException;

abstract class AbstractPackage implements StepInterface {
  protected string $packageName;
  protected CommandBuilder $commandBuilder;

  /**
   * @param array<string, string> $input
   */
  public static function fromArray(array $input): static {
    assert(
      isset($input['packageName']),
      new InvalidArgumentException('$input must have a "packageName" key')
    );

    return new static(
      new CommandBuilder(),
      $input['packageName']
    );
  }

  public function __construct(CommandBuilder $commandBuilder, string $packageName) {
    $packageName = trim($packageName);
    assert(
      $packageName !== '',
      new InvalidArgumentException('$packageName must not be empty')
    );

    $this->commandBuilder = $commandBuilder;

    $this->packageName = $packageName;
  }

  public function getPackageName(): string {
    return $this->packageName;
  }

  abstract public function getCommand(): Command;
}
