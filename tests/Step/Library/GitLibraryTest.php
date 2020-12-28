<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step\Library\GitLibrary;

use BuildEngine\Step\Library\GitLibrary;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GitLibraryTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "sourceUrl" key');

    GitLibrary::fromArray([]);
  }

  public function testInvalidSourceUrl(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$sourceUrl must contain a valid url');

    GitLibrary::fromArray(
      ['sourceUrl' => '']
    );
  }

  public function testValidSourceUrl(): void {
    $gitLibrary = GitLibrary::fromArray(
      ['sourceUrl'  => 'https://github.com/php-amqp/php-amqp']
    );

    $this->assertSame('https://github.com/php-amqp/php-amqp', $gitLibrary->getSourceUrl());
    $this->assertSame('/tmp/libsrc', $gitLibrary->getSourcePath());
    $this->assertSame(
      "git 'clone' '--recursive' '--depth=1' 'https://github.com/php-amqp/php-amqp' '/tmp/libsrc'",
      $gitLibrary->getCommand()->toString()
    );
  }
}
