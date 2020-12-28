<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

use Ramsey\Collection\AbstractCollection;

final class ArgumentCollection extends AbstractCollection {
  public function getType(): string {
    return ArgumentInterface::class;
  }

  public function toArray(): array {
    return array_map(
      function (ArgumentInterface $item): string {
        return $item->getValue();
      },
      $this->data
    );
  }
}
