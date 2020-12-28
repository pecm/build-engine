<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\OperatingSystem\Debian;

use BuildEngine\Step\OperatingSystem\Debian\AptSource;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AptSourceTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "sourceUrl" key');

    AptSource::fromArray([]);
  }

  public function testInvalidSourceUrl(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$sourceUrl must contain a valid url');

    AptSource::fromArray(
      ['sourceUrl' => '']
    );
  }

  public function testValidSourceUrl(): void {
    $aptSource = AptSource::fromArray(
      ['sourceUrl'  => 'https://example.com/apt-source/']
    );

    $this->assertSame('https://example.com/apt-source/', $aptSource->getSourceUrl());
    $this->assertMatchesRegularExpression(
      "/echo 'https:\/\/example\.com\/apt-source\/' >> '\/etc\/apt\/sources\.list\.d\/[0-9a-f]+\.list'/",
      $aptSource->getCommand()->toString()
    );
  }
}
