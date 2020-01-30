<?php


namespace Laravelizer;


use Illuminate\Support\Facades\File;

class Filesystem
{
    public function write($path, $contents): void
    {
        $path = substr($path, 0, strrpos( $path, '/'));
        $this->ensureDirectoryExists($path);
        File::put($path, $contents);
    }

    public function read($path): string
    {
        return File::get($path);
    }

    public function ensureDirectoryExists($path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, $recursive = true, $force = true);
        }
    }
}