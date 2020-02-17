<?php

namespace Laravelizer;

use Illuminate\Support\Facades\File;

class Filesystem
{
    public function write($path, $contents): void
    {
        // @todo - Remove feature flag
        if (config('env.ff-laravelizer')) {
            dump($contents);

            return;
        }

        $this->ensureDirectoryExists($path);
        File::put($path, $contents);
    }

    public function read($path): string
    {
        return File::get($path);
    }

    public function ensureDirectoryExists($path): void
    {
        if ($this->isFileName($path)) {
            $path = substr($path, 0, strrpos($path, '/'));
        }
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, $recursive = true, $force = true);
        }
    }

    private function isFileName($path)
    {
        return count(explode('.', $path)) > 1;
    }
}
