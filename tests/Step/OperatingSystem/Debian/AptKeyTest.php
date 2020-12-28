<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\OperatingSystem\Debian;

use BuildEngine\Step\OperatingSystem\Debian\AptKey;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AptKeyTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "sourceUrl" key');

    AptKey::fromArray([]);
  }

  public function testInvalidFileUrl(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$sourceUrl must contain a valid url');

    AptKey::fromArray(
      ['sourceUrl' => '']
    );
  }

  public function testValidFileUrl(): void {
    $aptKey = AptKey::fromArray(
      ['sourceUrl'  => 'https://example.com/apt-key']
    );

    $this->assertSame('https://example.com/apt-key', $aptKey->getSourceUrl());
    $this->assertSame(
      "curl 'https://example.com/apt-key' | apt-key 'add' '-'",
      $aptKey->getCommand()->toString()
    );
  }
}
