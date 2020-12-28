<?php
declare(strict_types = 1);

namespace BuildEngine\Step\OperatingSystem\Debian;

use BuildEngine\Command\Command;
use BuildEngine\Step\OperatingSystem\AbstractPackage;

final class Package extends AbstractPackage {
  public function getCommand(): Command {
    return $this->commandBuilder
      ->setCommand('apt')
      ->addArgument('install')
      ->addArgument('-y')
      ->addArgument('--no-install-recommends')
      ->addArgument($this->getPackageName())
      ->build();
  }
}
