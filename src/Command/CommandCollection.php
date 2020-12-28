<?php
declare(strict_types = 1);

namespace BuildEngine\Command;

use Ramsey\Collection\AbstractCollection;

final class CommandCollection extends AbstractCollection {
  public function getType(): string {
    return Command::class;
  }

  public function toArray(): array {
    return array_map(
      function (Command $item): string {
        return $item->toString();
      },
      $this->data
    );
  }
}
