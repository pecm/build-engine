<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\OperatingSystem\Debian;

use BuildEngine\Step\OperatingSystem\Debian\Package;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "packageName" key');

    Package::fromArray([]);
  }

  public function testInvalidPackageName(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$packageName must not be empty');

    Package::fromArray(
      ['packageName' => '']
    );
  }

  public function testValidPackageName(): void {
    $package = Package::fromArray(
      ['packageName'  => 'nano']
    );

    $this->assertSame('nano', $package->getPackageName());
    $this->assertSame(
      "apt 'install' '-y' '--no-install-recommends' 'nano'",
      $package->getCommand()->toString()
    );
  }
}
