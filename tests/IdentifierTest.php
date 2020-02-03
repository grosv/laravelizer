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
    public function can_find_surname() {
        $rows = collect(['Sandoval', 'Douglas', 'Stevens', 'Smith', 'Jones', 'Johnson']);

        $identify = new Identifier($rows);

        $this->assertEquals('last_name', $identify->execute());
    }
}