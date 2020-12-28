<?php
declare(strict_types = 1);

namespace BuildEngine\Step\OperatingSystem\Debian;

use BuildEngine\Command\Command;
use BuildEngine\Command\CommandBuilder;
use BuildEngine\Step\StepInterface;
use InvalidArgumentException;

final class AptSource implements StepInterface {
  private string $sourceUrl;
  private CommandBuilder $commandBuilder;

  /**
   * @param array<string, string> $input
   */
  public static function fromArray(array $input): self {
    assert(
      isset($input['sourceUrl']),
      new InvalidArgumentException('$input must have a "sourceUrl" key')
    );

    return new self(
      new CommandBuilder(),
      $input['sourceUrl']
    );
  }

  public function __construct(CommandBuilder $commandBuilder, string $sourceUrl) {
    assert(
      filter_var($sourceUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === $sourceUrl,
      new InvalidArgumentException('$sourceUrl must contain a valid url')
    );

    $this->commandBuilder = $commandBuilder;

    $this->sourceUrl = $sourceUrl;
  }

  public function getSourceUrl(): string {
    return $this->sourceUrl;
  }

  public function getCommand(): Command {
    return $this->commandBuilder
      ->setCommand('echo')
      ->addArgument($this->getSourceUrl())
      ->appendTo(
        sprintf(
          '/etc/apt/sources.list.d/%s.list',
          bin2hex(random_bytes(10))
        )
      )
      ->build();
  }
}
