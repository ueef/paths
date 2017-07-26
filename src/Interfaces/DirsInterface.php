<?php

namespace Ueef\Paths\Interfaces {

    interface DirsInterface
    {
        public function set(string $key, string $path): void;
        public function get(string $key, bool $makeDir = true): string;
        public function setRoot(string $path): void;
    }
}
