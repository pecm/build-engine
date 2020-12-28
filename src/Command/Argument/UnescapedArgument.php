<?php
declare(strict_types = 1);

namespace BuildEngine\Command\Argument;

use InvalidArgumentException;

final class UnescapedArgument implements ArgumentInterface {
  private const APPEND_TO = '>>';
  private const PIPE_TO = '|';
  private const REDIRECT_TO = '>';

  private string $value;

  public static function pipeTo(): self {
    return new self(self::PIPE_TO);
  }

  public static function redirectTo(): self {
    return new self(self::REDIRECT_TO);
  }

  public static function appendTo(): self {
    return new self(self::APPEND_TO);
  }

  public function __construct(string $value) {
    $value = trim($value);

    assert(
      $value !== '',
      new InvalidArgumentException('$value must not be empty')
    );

    $this->value = $value;
  }

  public function getValue(): string {
    return $this->value;
  }
}
