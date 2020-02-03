<?php

namespace Laravelizer;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravelizer\Types\EnumType;
use Laravelizer\Types\GeometryType;

class Database
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;

        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'customEnum');
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('geometry', 'customGeometry');
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
                $col = [
                    'connection' => $this->connection,
                    'table' => $table,
                    'name' => $column->getName(),
                    'type' => $column->getType()->getName(),
                    'length' => $column->getLength(),
                    'precision' => $column->getPrecision(),
                    'scale' => $column->getScale(),
                    'unsigned' => $column->getUnsigned(),
                    'fixed' => $column->getFixed(),
                    'default' => $column->getDefault(),
                    'autoincrement' => $column->getAutoincrement(),
                    'notnull' => $column->getNotNull(),
                    'options' => $column->getPlatformOptions(),
                    'definition' => $column->getColumnDefinition(),
                    'comment' => $column->getComment(),
                ];

                $migration = new Migration($col);
                $col['migration'] = $migration->execute();

                $factory = new Factory($col);
                $col['factory'] = $factory->execute();

                $columns->push($col);


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