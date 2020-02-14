<?php
declare(strict_types=1);

namespace Ueef\Paths\Tests
{
    use Ueef\Paths\Dir;
    use PHPUnit\Framework\TestCase;

    class DirTest extends TestCase
    {
        public function testGetUrl()
        {
            $argDir = uniqid();
            $argRoot = sys_get_temp_dir();


            $dir = new Dir($argRoot, $argDir);

            $this->assertEquals("/{$argDir}", $dir->getUrl(""));

            foreach (["a", "a/", "/a", "/a/", "a/b", "a/b/", "/a/b", "/a/b/"] as $filename) {
                $this->assertEquals("/{$argDir}/{$filename}", $dir->getUrl($filename));
            }
        }

        public function testGetPath()
        {

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
}