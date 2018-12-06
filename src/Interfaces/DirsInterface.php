<?php
declare(strict_types=1);

namespace Ueef\Paths\Interfaces;

interface DirsInterface
{
    public function set(string $key, string $path): void;
    public function get(string $key, bool $makeDir = true): string;
    public function setRoot(string $path): void;
}
