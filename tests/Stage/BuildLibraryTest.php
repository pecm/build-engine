<?php
declare(strict_types = 1);

namespace BuildEngine\Test\Stage;

use BuildEngine\Stage\BuildLibrary;
use BuildEngine\Step\Library\GitLibrary;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BuildLibraryTest extends TestCase {
  private function getLibrary(): GitLibrary {
    return GitLibrary::fromArray(
      ['sourceUrl'  => 'https://example.com/user/mylib']
    );
  }

  public function testFromArrayWithEmptyInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input must have a "library" key');

    BuildLibrary::fromArray([]);
  }

  public function testFromArrayWithInvalidLibraryInput(): void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('$input[\'library\'] must be an instance of BuildEngine\Step\Library\Library');

    BuildLibrary::fromArray(
      [
        'library' => ''
      ]
    );
  }

  public function testSimpleAndValidLibraryBuild(): void {
    $library  = $this->getLibrary();
    $libBuild = BuildLibrary::fromArray(
      [
        'library' => $library,
        'buildFlag' => '--with-mylib'
      ]
    );

    $this->assertSame($library, $libBuild->getLibrary());
    $this->assertSame('--with-mylib', $libBuild->getBuildFlag());
    $this->assertSame('', $libBuild->getBuildPath());
    $this->assertSame(
      [
        "git 'clone' '--recursive' '--depth=1' 'https://example.com/user/mylib' '/tmp/libsrc'",
        "cd '/tmp/libsrc'",
        "./configure '--with-mylib'",
        'make',
        "make 'test'"
      ],
      $libBuild->build()->toArray()
    );
  }

  public function testLibraryBuildWithCustomBuildPath(): void {
    $libBuild = BuildLibrary::fromArray(
      [
        'library' => $this->getLibrary(),
        'buildFlag' => '--enable-mylib',
        'buildPath' => './extension'
      ]
    );

    $this->assertSame('--enable-mylib', $libBuild->getBuildFlag());
    $this->assertSame('./extension', $libBuild->getBuildPath());
    $this->assertSame(
      [
        "git 'clone' '--recursive' '--depth=1' 'https://example.com/user/mylib' '/tmp/libsrc'",
        "cd '/tmp/libsrc/extension'",
        "./configure '--enable-mylib'",
        'make',
        "make 'test'"
      ],
      $libBuild->build()->toArray()
    );
  }

  public function testLibraryBuildWithoutBuildFlag(): void {
    $libBuild = BuildLibrary::fromArray(
      [
        'library' => $this->getLibrary()
      ]
    );

    $this->assertSame('', $libBuild->getBuildFlag());
    $this->assertSame('', $libBuild->getBuildPath());
    $this->assertSame(
      [
        "git 'clone' '--recursive' '--depth=1' 'https://example.com/user/mylib' '/tmp/libsrc'",
        "cd '/tmp/libsrc'",
        './configure',
        'make',
        "make 'test'"
      ],
      $libBuild->build()->toArray()
    );
  }
}
