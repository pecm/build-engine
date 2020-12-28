<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\OperatingSystem\Alpine;

use BuildEngine\Step\OperatingSystem\Alpine\UntrustedPackage;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UntrustedPackageTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "packageName" key');

    UntrustedPackage::fromArray([]);
  }

  public function testInvalidPackageName(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$packageName must not be empty');

    UntrustedPackage::fromArray(
      ['packageName' => '']
    );
  }

  public function testValidPackageName(): void {
    $package = UntrustedPackage::fromArray(
      ['packageName'  => 'nano']
    );

    $this->assertSame('nano', $package->getPackageName());
    $this->assertSame(
      "apk 'add' '--allow-untrusted' 'nano'",
      $package->getCommand()->toString()
    );
  }
}
