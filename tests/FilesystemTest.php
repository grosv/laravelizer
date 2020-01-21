<?php

namespace Tests;

use Laravelizer\Actions\Write;

class FilesystemTest extends TestCase
{
    /** @test */
    public function can_create_directory()
    {
        $this->assertDirectoryNotExists(config('laravelizer.model.path'));
        $write = new Write();
        $write->ensureDirectoryExists(config('laravelizer.model.path'));
        $this->assertDirectoryExists(config('laravelizer.model.path'));
    }
}