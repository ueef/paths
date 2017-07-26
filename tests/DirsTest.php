<?php
declare(strict_types=1);

use Ueef\Paths\Dirs;
use Ueef\Paths\Exceptions\UndefinedDirException;
use Ueef\Paths\Exceptions\CannotMakeDirException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Email
 */
final class StorageTest extends TestCase
{
    /** @var Dirs */
    private $dirs;

    /** @var string */
    private $root;


    public function setUp()
    {
        $this->dirs = new Dirs;
        $this->root = $this->makeRootPath();
    }

    public function tearDown()
    {
        $this->rmdir($this->root);
    }

    /** @dataProvider dirsProvider */
    public function testGet(string $key, string $path, string $expected)
    {
        $this->dirs->set($key, $path);
        $this->dirs->setRoot($this->root);
        $_path = $this->dirs->get($key);
        $this->assertEquals($this->root . $expected, $_path);
        $this->assertTrue(is_dir($_path));
    }

    /** @dataProvider dirsProvider */
    public function testGetWithoutMakeDir(string $key, string $path, string $expected)
    {
        $this->dirs->set($key, $path);
        $this->dirs->setRoot($this->root);
        $_path = $this->dirs->get($key, false);
        $this->assertEquals($this->root . $expected, $_path);
        $this->assertFalse(is_dir($_path));
    }

    public function testGetUndefinedDir()
    {
        $this->expectException(UndefinedDirException::class);

        $dirs = new Dirs;
        $dirs->get(uniqid());
    }

    public function testMakeDir()
    {
        $this->expectException(CannotMakeDirException::class);

        $path = uniqid();
        $dirs = new Dirs;
        $dirs->set($path, $path);
        $dirs->get($path, true);
    }

    public function dirsProvider()
    {
        return [
            ['a', 'a', '/a'],
            ['a', '/a', '/a'],
            ['a', 'a/', '/a'],
            ['a', '/a/', '/a'],
        ];
    }

    private function makeRootPath(): string
    {
        return sys_get_temp_dir() . '/' . uniqid();
    }

    private function rmdir(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (array_diff(scandir($path), ['.', '..']) as $file) {
            $file = $path . '/' . $file;

            if (is_dir($file)) {
                $this->rmdir($file);
            } else {
                unlink($file);
            }
        }
    }
}