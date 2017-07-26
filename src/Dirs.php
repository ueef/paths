<?php

namespace Ueef\Paths {

    use Throwable;
    use Ueef\Paths\Interfaces\PathsInterface;
    use Ueef\Paths\Exceptions\UndefinedDirException;
    use Ueef\Paths\Exceptions\UndefinedRootException;
    use Ueef\Paths\Exceptions\CannotMakeDirException;

    class Dirs implements PathsInterface
    {
        const MODE = 0755;

        const EXISTS = 1;
        const CREATE = 2;

        /** @var string */
        private $root;

        /** @var array */
        protected $dirs = [];


        public function __construct(array $paths = [])
        {
            foreach ($paths as $key => $path) {
                $this->set($key, $path);
            }
        }

        public function set(string $key, string $path): void
        {
            $this->dirs[$key] = $this->correctPath($path);
        }

        public function get(string $key, bool $makeDir = true): string
        {
            if (!key_exists($key, $this->dirs)) {
                throw new UndefinedDirException(['Undefined dir "%s"', $key]);
            }

            $path = $this->resolvePath($this->dirs[$key]);

            if ($makeDir) {
                $this->makeDir($path);
            }

            return $path;
        }

        public function setRoot(string $path): void
        {
            $this->root = $this->correctPath($path);
        }

        private function resolvePath($dir)
        {
            if (!$this->root) {
                throw new UndefinedRootException(['Root is undefined', $key]);
            }

            return $this->root . $dir;
        }

        private function makeDir($path)
        {
            if (!is_dir($path)) {
                try {
                    $success = mkdir($path, self::MODE, true);
                } catch (Throwable $e) {
                    $success = false;
                }

                if (!$success) {
                    throw new CannotMakeDirException(['Can\'t make directory "%s"', $path]);
                }
            }
        }

        private function correctPath(string $path)
        {
            return '/' . trim($path, '/');
        }
    }
}
