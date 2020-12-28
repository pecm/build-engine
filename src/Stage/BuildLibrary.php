<?php
declare(strict_types = 1);

namespace BuildEngine\Stage;

use BuildEngine\Command\CommandBuilder;
use BuildEngine\Command\CommandCollection;
use BuildEngine\Step\Library\LibraryInterface;
use InvalidArgumentException;

final class BuildLibrary {
  private CommandBuilder $commandBuilder;
  private LibraryInterface $library;
  private string $buildFlag;
  private string $buildPath;

  private function resolveRealBuildPath(): string {
    if ($this->buildPath === '') {
      return $this->library->getSourcePath();
    }

    $buildPath = preg_replace('/[^a-z0-9_\/\. -]/', '', $this->buildPath);
    if ($buildPath === null) {
      return $this->library->getSourcePath();
    }

    $buildPath = preg_replace('/(\.{1,2}\/)+/', '', $buildPath);
    if ($buildPath === null) {
      return $this->library->getSourcePath();
    }

    return sprintf(
      '%s/%s',
      $this->library->getSourcePath(),
      trim($buildPath, '/')
    );
  }

  /**
   * @param array<string, string> $input
   */
  public static function fromArray(array $input): self {
    assert(
      isset($input['library']),
      new InvalidArgumentException('$input must have a "library" key')
    );

    assert(
      $input['library'] instanceof LibraryInterface,
      new InvalidArgumentException(
        sprintf(
          '$input[\'library\'] must be an instance of %s',
          LibraryInterface::class
        )
      )
    );

    return new self(
      new CommandBuilder(),
      $input['library'],
      $input['buildFlag'] ?? '',
      $input['buildPath'] ?? ''
    );
  }

  public function __construct(
    CommandBuilder $commandBuilder,
    LibraryInterface $library,
    string $buildFlag = '',
    string $buildPath = ''
  ) {
    $this->commandBuilder = $commandBuilder;

    $this->library = $library;

    $this->buildFlag = $buildFlag;
    $this->buildPath = $buildPath;
  }

  public function getLibrary(): LibraryInterface {
    return $this->library;
  }

  public function getBuildFlag(): string {
    return $this->buildFlag;
  }

  public function getBuildPath(): string {
    return $this->buildPath;
  }

  public function build(): CommandCollection {
    $cmdCollection = new CommandCollection();
    $cmdCollection->add($this->library->getCommand());

    $cmdCollection->add(
      $this->commandBuilder
        ->setCommand('cd')
        ->addArgument($this->resolveRealBuildPath())
        ->build()
    );

    $this->commandBuilder->reset();
    $this->commandBuilder->setCommand('./configure');
    if ($this->getBuildFlag() !== '') {
      $this->commandBuilder->addArgument($this->getBuildFlag());
    }

    $cmdCollection->add(
      $this->commandBuilder->build()
    );

    $this->commandBuilder->reset();
    $cmdCollection->add(
      $this->commandBuilder
        ->setCommand('make')
        ->build()
    );

    $this->commandBuilder->reset();
    $cmdCollection->add(
      $this->commandBuilder
        ->setCommand('make')
        ->addArgument('test')
        ->build()
    );

    return $cmdCollection;
  }
}
