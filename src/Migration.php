<?php

namespace Laravelizer;

use Illuminate\Support\Facades\DB;

class Migration
{
    protected $column;

    public function __construct($column)
    {
        $this->column = $column;
    }

    public function execute()
    {
        $method = $this->column['type'];
        return method_exists($this, $method) ? $this->$method() : $this->missingType();
    }

    protected function smallint()
    {
        $migration = '$table->smallInteger("' . $this->column['name'] . '")';
        $migration .= $this->unsigned();
        return $migration . ';';
    }

    protected function integer()
    {
        $migration = '$table->integer("' . $this->column['name'] . '")';
        $migration .= $this->unsigned();
        return $migration . ';';
    }

    protected function float()
    {
        return '$table->float("' . $this->column['name'] . $this->precisionAndScale() . '")' . $this->modifiers() . ';';
    }

    protected function decimal()
    {
        return '$table->decimal("' . $this->column['name'] . $this->precisionAndScale() . '")' . $this->modifiers() . ';';
    }

    protected function bigint()
    {
        $migration = '$table->bigInteger("' . $this->column['name'] . '")';
        $migration .= $this->unsigned();
        return $migration . ';';
    }

    protected function string(): string
    {
        $migration = '$table->string("' . $this->column['name'] . $this->column['length'] . '")';
        $migration .= $this->modifiers();
        return $migration . ';';
    }

    protected function text(): string
    {
        $migration = '$table->text("' . $this->column['name'] . '")';
        $migration .= $this->modifiers();
        return $migration . ';';
    }

    protected function enum(): string
    {
        $distinct = '"' . DB::connection($this->column['connection'])->table($this->column['table'])->select($this->column['name'])->groupBy($this->column['name'])->get()->pluck($this->column['name'])->join('","') . '"';
        return '$table->enum("' . $this->column['name'] . ', [' . $distinct . '])' . $this->modifiers() . ';';
    }

    protected function simple_array(): string
    {
        return '$table->json("' . $this->column['name'] . '")' . $this->modifiers() . ';';
    }

    protected function blob(): string
    {
        return '$table->binary("' . $this->column['name'] . '")' . $this->modifiers() . ';';
    }


    protected function datetime(): string
    {
        $migration = '$table->timeStamp("' . $this->column['name'] . '")';
        $migration .= $this->modifiers();

        return $migration . ';';
    }

    protected function date()
    {
        return '$table->date("' . $this->column['name'] . '")' . $this->modifiers() . ';';
    }

    protected function geometry()
    {
        return '$table->geometry("' . $this->column['name'] . '")' . $this->modifiers() . ';';
    }

    protected function boolean()
    {
        $migration = '$table->boolean("' . $this->column['name'] . '")';
        $migration .= $this->modifiers();
        return $migration . ';';
    }

    private function modifiers()
    {
        $string = '';
        $modifiers = [
            'nullable' => ['smallint', 'integer', 'bigint', 'string', 'text'],
            'unsigned' => ['smallint', 'integer', 'bigint'],
            'precisionAndScale' => ['float', 'decimal'],
            'useCurrent' => ['datetime'],
            'autoIncrement' => ['smallint', 'integer', 'bigint'],
            'collation' => ['string', 'text'],
            'charset' => ['string', 'text'],
            'default' => ['string', 'text', 'boolean', 'integer', 'smallint', 'bigint', 'float', 'decimal'],
            'comment' => ['*']

        ];

        foreach ($modifiers as $k => $v) {
            if (in_array($this->column['type'], $v) || in_array('*', $v)) {
                $string .= $this->$k();
            }
        }
        return $string;
    }

    private function length()
    {
        return is_null($this->column['length']) ? '' : ', ' . $this->column['length'];
    }

    private function precisionAndScale()
    {
        return !is_null($this->column['precision'] && !is_null($this->column['scale'])) ?
             $this->column['precision'] . ', ' . $this->column['scale'] : '';
    }



    private function nullable()
    {
        return $this->column['notnull'] ? '' : '->nullable()';
    }

    private function unsigned()
    {
        return $this->column['unsigned'] ? '->unsigned()' : '';
    }



    private function useCurrent()
    {
        return $this->column['default'] === 'CURRENT_TIMESTAMP' ? '->useCurrent()' : '';
    }

    private function autoIncrement()
    {
        return $this->column['autoincrement'] ? '->autoIncrement()' : '';
    }

    private function collation()
    {
        return isset($this->column['options']['collation']) ? '->collation("' . $this->column['options']['collation'] . '")' : '';
    }

    private function charset()
    {
        return isset($this->column['options']['charset']) ? '->charset("' . $this->column['options']['charset'] . '")' : '';
    }

    private function comment()
    {
        return is_null($this->column['comment']) ? '' : '->comment("' . $this->column['comment'] . '")';
    }

    private function default()
    {
        return is_null($this->column['default']) ? '' : '->default("' . $this->column['default'] . '")';
    }

    private function missingType()
    {
        dd('Missing type ' . $this->column['type']);
    }

}