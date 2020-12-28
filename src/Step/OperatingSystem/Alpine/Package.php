<?php
declare(strict_types = 1);

namespace BuildEngine\Step\OperatingSystem\Alpine;

use BuildEngine\Command\Command;
use BuildEngine\Step\OperatingSystem\AbstractPackage;

final class Package extends AbstractPackage {
  public function getCommand(): Command {
    return $this->commandBuilder
      ->setCommand('apk')
      ->addArgument('add')
      ->addArgument('--no-cache')
      ->addArgument($this->getPackageName())
      ->build();
  }
}
