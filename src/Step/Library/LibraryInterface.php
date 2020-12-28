<?php
declare(strict_types = 1);

namespace BuildEngine\Step\Library;

use BuildEngine\Step\StepInterface;

interface LibraryInterface extends StepInterface {
  public function getSourceUrl(): string;
  public function getSourcePath(): string;
}
