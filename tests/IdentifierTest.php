<?php

namespace Tests;

use Laravelizer\Filesystem;
use Laravelizer\Identifier;
use Laravelizer\Models\Surname;

class IdentifierTest extends TestCase
{
    /** @test */
    public function can_consume_sushi()
    {
        $this->assertEquals(Surname::first()->name, 'smith');
    }

    /** @test */
    public function can_find_paragraph()
    {
        $rows = collect([
            'I need to leave soon to pick up my son. I need to leave soon to pick up my son.',
            'What time are we meeting today? What time are we meeting today?',
            'Does any of this stuff really matter in the end? Does any of this stuff really matter in the end?',
        ]);

        $identify = new Identifier($rows);

        $this->assertEquals('paragraph', $identify->execute());
    }

    /** @test */
    public function can_find_surname()
    {
        $rows = collect(['Sandoval', 'Douglas', 'Stevens', 'Smith', 'Jones', 'Johnson']);

        $identify = new Identifier($rows);

        $this->assertEquals('last_name', $identify->execute());
    }

    /** @test */
    public function can_find_sentence()
    {
        $rows = collect([
            'I need to leave soon to pick up my son.',
            'What time are we meeting today?',
            'Does any of this stuff really matter in the end?',
        ]);

        $identify = new Identifier($rows);

        $this->assertEquals('sentence', $identify->execute());
    }
}