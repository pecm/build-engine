<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Step;

use BuildEngine\Step\Download;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DownloadTest extends TestCase {
  public function testFromArrayWithInvalidInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "fileUrl" key');

    Download::fromArray([]);
  }

  public function testInvalidFileUrl(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$fileUrl must contain a valid url');

    Download::fromArray(
      ['fileUrl' => '']
    );
  }

  public function testWithFileName(): void {
    $download = Download::fromArray(
      [
        'fileUrl'  => 'https://example.com/file.txt',
        'fileName' => 'xpto.txt'
      ]
    );

    $this->assertSame('https://example.com/file.txt', $download->getFileUrl());
    $this->assertSame('xpto.txt', $download->getFileName());
    $this->assertSame(
      "curl 'https://example.com/file.txt' '--output' 'xpto.txt' '--silent'",
      $download->getCommand()->toString()
    );
  }

  public function testResolveFileNameFromUrlWithAValidFileName(): void {
    $download = Download::fromArray(
      ['fileUrl' => 'https://example.com/file.txt']
    );

    $this->assertSame('https://example.com/file.txt', $download->getFileUrl());
    $this->assertSame('file.txt', $download->getFileName());
    $this->assertSame(
      "curl 'https://example.com/file.txt' '--output' 'file.txt' '--silent'",
      $download->getCommand()->toString()
    );
  }

  public function testResolveFileNameFromUrlWithAnInvalidFileName(): void {
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Could not resolve a valid file name from the $fileUrl');

    Download::fromArray(
      ['fileUrl' => 'https://example.com/']
    );
  }
}
