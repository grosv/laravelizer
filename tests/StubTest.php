<?php

namespace Tests;

use Carbon\Carbon;
use Laravelizer\Stub;

class StubTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function testSetTable()
    {
        $stub = new Stub();
        $stub->setTable('this');
        $this->assertSame('this', $stub->assign['table']);
    }

    public function testSetModelNamespace()
    {
        $stub = new Stub();
        $stub->setModelNamespace('mnsp');
        $this->assertSame('mnsp', $stub->assign['model_namespace']);
    }

    public function testSetColumns()
    {
        $stub = new Stub();
        $stub->setColumns(collect(['this' => 'that', 'that' => 'this']));
        $this->assertEquals(collect(['this' => 'that', 'that' => 'this']), $stub->assign['columns']);
    }

    public function testMigration()
    {

    }

    public function testModel()
    {

    }

    public function testFactory()
    {

    }

    public function testSetSoftDeletes()
    {
        $stub = new Stub();
        $stub->setSoftDeletes(0);
        $this->assertFalse($stub->assign['soft_deletes']);
        $stub->setSoftDeletes(true);
        $this->assertTrue($stub->assign['soft_deletes']);
        $stub->setSoftDeletes(0);
        $this->assertFalse($stub->assign['soft_deletes']);
        $stub->setSoftDeletes('yup');
        $this->assertTrue($stub->assign['soft_deletes']);
    }

    public function testShare()
    {

    }

    public function testBuild()
    {

    }

    public function testSetConnection()
    {
        $stub = new Stub();
        $stub->setConnection('mysql');
        $this->assertSame('mysql', $stub->assign['connection']);
    }

    public function testSetModelClassName()
    {
        $stub = new Stub();
        $stub->setModelClassName('Classy');
        $this->assertSame('Classy', $stub->assign['model_name']);
    }

    public function testSetOptions()
    {
        $stub = new Stub();
        $ts = Carbon::parse('now');
        $stub->setOptions(['created_at' => $ts]);
        $this->assertSame($ts, $stub->assign['created_at']);
        $stub->setOptions(['nosuchoption' => 'fake']);
        $this->assertArrayNotHasKey('nosuchoption', $stub->assign);
    }
}
