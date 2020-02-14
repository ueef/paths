<?php
declare(strict_types=1);

namespace Ueef\Paths\Interfaces;

interface DirInterface
{
    public function getUrl(string $filename, string $dir = ""): string;
    public function getPath(string $filename, string $dir = ""): string;
}
