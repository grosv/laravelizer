<?php

namespace Tests;

use Carbon\Carbon;
use Laravelizer\Stub;

class StubTest extends TestCase
{
    protected $stub;
    protected $model;
    protected $migration;
    protected $factory;
    protected $test;
    protected $nova;
    protected $columns_json = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->stub = new Stub();
        $this->stub->setTable('people');
        $this->stub->setModelNamespace('App');
        $this->stub->setColumns(collect(json_decode($this->columns_json, true)));
        $this->stub->setModelClassName('Person');
        $this->stub->setConnection('mysql');
        $this->model = $this->stub->model('app\Person.php');
        $this->factory = $this->stub->factory('database/factories/PersonFactory.php');
        $this->migration = $this->stub->migration('database/migrations/0000_00_00_000000_create_people_table.php');
    }

    /** @test */
    public function testSetTable()
    {
        $this->assertSame('people', $this->stub->assign['table']);
    }

    public function testSetModelNamespace()
    {
        $this->assertSame('App', $this->stub->assign['model_namespace']);
    }

    public function testSetColumns()
    {
        $this->assertEquals(collect(json_decode($this->columns_json, true)), $this->stub->assign['columns']);
    }

    public function testMigration()
    {


    }

    public function testModel()
    {
        $this->assertStringContainsString('class Person extends Model', $this->model);
        $this->assertStringContainsString('namespace App', $this->model);
        $this->assertStringContainsString('protected $table = "people"', $this->model);
        $this->assertStringContainsString('protected $connection = "mysql"', $this->model);
    }

    public function testFactory()
    {

    }

    public function testSetSoftDeletes()
    {
        $this->stub->setSoftDeletes(0);
        $this->assertFalse($this->stub->assign['soft_deletes']);
        $this->stub->setSoftDeletes(true);
        $this->assertTrue($this->stub->assign['soft_deletes']);
        $this->stub->setSoftDeletes(0);
        $this->assertFalse($this->stub->assign['soft_deletes']);
        $this->stub->setSoftDeletes('yup');
        $this->assertTrue($this->stub->assign['soft_deletes']);
    }


    public function testSetConnection()
    {
        $this->assertSame('mysql', $this->stub->assign['connection']);
    }

    public function testSetModelClassName()
    {
        $this->assertSame('Person', $this->stub->assign['model_name']);
    }

    public function testSetOptions()
    {

        $ts = Carbon::parse('now');
        $this->stub->setOptions(['created_at' => $ts]);
        $this->assertSame($ts, $this->stub->assign['created_at']);
        $this->stub->setOptions(['nosuchoption' => 'fake']);
        $this->assertArrayNotHasKey('nosuchoption', $this->stub->assign);
    }
}
