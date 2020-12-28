<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Stage\Extension;

use BuildEngine\Stage\Extension\PeclExtension;
use BuildEngine\Step\Library\GitLibrary;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PeclExtensionTest extends TestCase {
  public function testFromArrayWithEmptyInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have an "extensionName" key');

    PeclExtension::fromArray([]);
  }

  public function testFromArrayWithInvalidExtensionInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input[\'extensionName\'] must not be empty');

    PeclExtension::fromArray(
      [
        'extensionName' => ''
      ]
    );
  }

  public function testSimpleAndValidExtensionInstall(): void {
    $pecl = PeclExtension::fromArray(
      [
        'extensionName' => 'my-ext'
      ]
    );

    $this->assertSame('my-ext', $pecl->getExtensionName());
    $this->assertSame(
      [
        "pecl 'install' 'my-ext'",
        "pecl 'run-tests' 'my-ext'"
      ],
      $pecl->build()->toArray()
    );
  }
}
