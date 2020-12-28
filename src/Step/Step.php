<?php
declare(strict_types = 1);

namespace BuildEngine\Step;

use BuildEngine\Command\Command;

interface Step {
  public function getCommand(): Command;
}
