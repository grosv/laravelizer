<?php

namespace Tests;

use Illuminate\Support\Collection;
use Laravelizer\Database;

class DatabaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

    }
    /** @test */
    public function can_list_database_tables()
    {

        $database = new Database('sakila');

        $this->assertEquals(["actor","address","category","city","country","customer","film","film_actor","film_category","film_text","inventory","language","payment","rental","staff","store"], $database->getTables());


    }
    /** @test */
    public function can_list_table_column_names()
    {
        $database = new Database('sakila');

        $this->assertEquals(['actor_id', 'first_name', 'last_name', 'last_update'], $database->getColumnNames('actor'));
    }

    /** @test */
    public function can_identify_column_types()
    {
        $database = new Database('sakila');

        $this->assertInstanceOf(Collection::class, $database->getColumns('address'));
    }

    /** @test */
    public function can_get_foreign_key_contraints()
    {
        $database = new Database('sakila');

        $this->assertInstanceOf(Collection::class, $database->getForeignKeyRestraints('address'));
    }
}