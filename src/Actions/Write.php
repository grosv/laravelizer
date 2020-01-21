<?php


namespace Laravelizer\Actions;


use Illuminate\Support\Facades\File;

class Write
{
    public function construct()
    {

    }

    public function ensureDirectoryExists($path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, $recursive = true, $force = true);
        }
    }
}