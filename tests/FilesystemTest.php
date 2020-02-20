<?php

namespace Tests;

use Laravelizer\Filesystem;

class FilesystemTest extends TestCase
{
    /**
     * @test
     * @group always
     */
    public function can_create_directory()
    {
        $this->assertDirectoryNotExists(config('laravelizer.model.path'));
        $write = new Filesystem();
        $write->ensureDirectoryExists(config('laravelizer.model.path'));
        $this->assertDirectoryExists(config('laravelizer.model.path'));
    }

    /**
     * @test
     * @group always
     */
    public function can_create_directory_from_filename()
    {
        $this->assertDirectoryNotExists(config('laravelizer.model.path'));
        $write = new Filesystem();
        $write->ensureDirectoryExists(config('laravelizer.model.path').'/is_file.txt');
        $this->assertDirectoryExists(config('laravelizer.model.path'));
    }

    /**
     * @test
     * @group always
     */
    public function can_write_file()
    {
        $this->assertFileNotExists(config('laravelizer.model.path').'/is_file.txt');
        $fs = new FileSystem();

        $fs->write(config('laravelizer.model.path').'/is_file.txt', 'Hello');

        $this->assertFileExists(config('laravelizer.model.path').'/is_file.txt');

        $this->assertSame('Hello', $fs->read(config('laravelizer.model.path').'/is_file.txt'));
    }
}
