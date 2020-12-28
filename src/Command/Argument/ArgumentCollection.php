<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

use Ramsey\Collection\AbstractCollection;

final class ArgumentCollection extends AbstractCollection {
  public function getType(): string {
    return Argument::class;
  }

  public function toArray(): array {
    return array_map(
      function (Argument $item): string {
        return $item->getValue();
      },
      $this->data
    );
  }
}
