<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\OperatingSystem\Alpine;

use BuildEngine\Step\OperatingSystem\Alpine\ApkSource;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ApkSourceTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "sourceUrl" key');

    ApkSource::fromArray([]);
  }

  public function testInvalidSourceUrl(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$sourceUrl must contain a valid url');

    ApkSource::fromArray(
      ['sourceUrl' => '']
    );
  }

  public function testValidSourceUrl(): void {
    $apkSource = ApkSource::fromArray(
      ['sourceUrl'  => 'https://example.com/apk-source/']
    );

    $this->assertSame('https://example.com/apk-source/', $apkSource->getSourceUrl());
    $this->assertSame(
      "echo 'https://example.com/apk-source/' >> '/etc/apk/repositories'",
      $apkSource->getCommand()->toString()
    );
  }
}
