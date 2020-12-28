<?php
declare(strict_types = 1);

namespace BuildEngine\Step;

use BuildEngine\Command\Command;
use BuildEngine\Command\CommandBuilder;
use InvalidArgumentException;
use RuntimeException;

final class Download implements StepInterface {
  private string $fileUrl;
  private string $fileName;
  private CommandBuilder $commandBuilder;

  private static function resolveFileNameFromUrl(string $fileUrl): string {
    $fileName = parse_url($fileUrl, PHP_URL_PATH);
    if ($fileName === null || $fileName === false || strpos($fileName, '.') === false) {
      throw new RuntimeException('Could not resolve a valid file name from the $fileUrl');
    }

    return basename($fileName);
  }

  /**
   * @param array<string, string> $input
   */
  public static function fromArray(array $input): self {
    assert(
      isset($input['fileUrl']),
      new InvalidArgumentException('$input must have a "fileUrl" key')
    );

    return new self(
      new CommandBuilder(),
      $input['fileUrl'],
      $input['fileName'] ?? null
    );
  }

  public function __construct(CommandBuilder $commandBuilder, string $fileUrl, string $fileName = null) {
    assert(
      filter_var($fileUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === $fileUrl,
      new InvalidArgumentException('$fileUrl must contain a valid url')
    );

    $this->commandBuilder = $commandBuilder;

    $this->fileUrl = $fileUrl;
    if ($fileName === null) {
      $fileName = self::resolveFileNameFromUrl($fileUrl);
    }

    $this->fileName = $fileName;
  }

  public function getFileUrl(): string {
    return $this->fileUrl;
  }

  public function getFileName(): string {
    return $this->fileName;
  }

  public function getCommand(): Command {
    return $this->commandBuilder
      ->setCommand('curl')
      ->addArgument($this->getFileUrl())
      ->addArgument('--output')
      ->addArgument($this->getFileName())
      ->addArgument('--silent')
      ->build();
  }
}
