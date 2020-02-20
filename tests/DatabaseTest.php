<?php

namespace Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Laravelizer\Database;

class DatabaseTest extends TestCase
{
    protected $tables = ['actor', 'address', 'category', 'city', 'country', 'customer', 'film', 'film_actor', 'film_category', 'film_text', 'inventory', 'language', 'payment', 'rental', 'staff', 'store'];

    public function setUp(): void
    {
        parent::setUp();

        exec('mysql chipperci < tests/sakila-db/sakila-schema.sql');
        exec('mysql chipperci < tests/sakila-db/sakila-data.sql');

    }

    /**
     * @test
     * @group db
     */
    public function can_list_database_tables()
    {
        $database = new Database(config('database.default'));
        foreach ($this->tables as $table) {
            $this->assertContains($table, $database->getTables());
        }
    }

    /**
     * @test
     * @group db
     */
    public function can_list_table_column_names()
    {
        $database = new Database(config('database.default'));

        $this->assertEquals(['actor_id', 'first_name', 'last_name', 'last_update'], $database->getColumnNames('actor'));
    }

    /**
     * @test
     * @group db
     */
    public function can_identify_column_types()
    {
        $database = new Database(config('database.default'));

        $this->assertInstanceOf(Collection::class, $database->getColumns($this->tables[array_rand($this->tables)]));
    }

    /**
     * @test
     * @group db
     */
    public function can_get_foreign_key_contraints()
    {
        $database = new Database(config('database.default'));

        $this->assertInstanceOf(Collection::class, $database->getForeignKeyRestraints($this->tables[array_rand($this->tables)]));
    }
}
