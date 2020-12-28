<?php
declare(strict_types = 1);

namespace BuildEngine\Step;

use BuildEngine\Command\Command;

interface StepInterface {
  public function getCommand(): Command;
}
