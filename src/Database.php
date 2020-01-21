<?php

namespace Laravelizer;

use Illuminate\Support\Facades\DB;

class Database
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
        DB::connection($this->connection)->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        DB::connection($this->connection)->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geometry', 'string');
    }

    public function getTables()
    {
        return DB::connection($this->connection)->getDoctrineSchemaManager()->listTableNames();
    }

    public function getColumnNames($table)
    {
        return array_keys(DB::connection($this->connection)->getDoctrineSchemaManager()->listTableColumns($table));
    }

    public function getColumns($table)
    {
        $columns = collect([]);
        foreach (DB::connection($this->connection)->getDoctrineSchemaManager()->listTableColumns($table) as $column) {

                $columns->push([
                    'name' => $column->getName(),
                    'type' => $column->getType()->getName(),
                    'length' => $column->getLength(),
                    'precision' => $column->getPrecision(),
                    'scale' => $column->getScale(),
                    'unsigned' => $column->getUnsigned(),
                    'fixed' => $column->getFixed(),
                    'notnull' => $column->getNotNull(),
                    'options' => $column->getPlatformOptions()
                ]);


        }
        return $columns;
    }

    public function getForeignKeyRestraints($table)
    {
        $constraints = collect([]);
        foreach (DB::connection($this->connection)->getDoctrineSchemaManager()->listTableForeignKeys($table) as $constraint) {
            if (is_object($constraint)) {
                $constraints->push([
                    'name' => $constraint->getName() ?? null,
                    'local_column' => $constraint->getLocalColumns()[0] ?? null,
                    'foreign_table' => $constraint->getForeignTableName() ?? null,
                    'foreign_column' => $constraint->getForeigncolumns()[0],
                    'onDelete' => $constraint->getOptions()['onDelete'] ?? null,
                    'onUpdate' => $constraint->getOptions()['onUpdate'] ?? null,
                ]);
            }
        }
        return $constraints;
    }

    protected function faker()
    {
        return 'return a faker type';
    }

    protected function nova()
    {
        return 'return a nova field';
    }
}