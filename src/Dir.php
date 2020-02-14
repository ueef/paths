<?php

declare(strict_types=1);

namespace Ueef\Paths;

use Throwable;
use Ueef\Paths\Exceptions\CannotMakeDirException;
use Ueef\Paths\Interfaces\DirInterface;

class Dir implements DirInterface
{
    private string $dir;
    private string $root;
    private int    $makingMode;

    public function __construct(string $root, string $dir, int $makingMode = 0755)
    {
        $this->dir = $this->correct($dir);
        $this->root = $this->correct($root);
        $this->makingMode = $makingMode;
    }

    public function getUrl(string $filename, string $dir = ""): string
    {
        return $this->dir . $this->correct($dir) . $this->correct($filename);
    }

    public function getPath(string $filename, string $dir = ""): string
    {
        $dir = $this->root . $this->dir . $this->correct($dir);
        $this->check($dir);

        return $dir . $this->correct($filename);
    }

    private function check(string $dir): void
    {
        if (is_dir($dir)) {
            return;
        }

        try {
            $success = mkdir($dir, $this->makingMode, true);
        } catch (Throwable $e) {
            $success = false;
        }

        if (!$success && !is_dir($dir)) {
            throw new CannotMakeDirException($dir);
        }
    }

    private function correct(string $path): string
    {
        $path = trim($path, "/");
        if ($path) {
            return "/" . $path;
        }

        return "";
    }
}