<?php

namespace Laravelizer;

class Nova
{
    protected $column;
    protected $name;

    public function __construct($column)
    {
        $this->column = $column;
        $this->name = strtolower($column['name']);
    }

    public function execute()
    {
        $type = $this->column['type'];

        return method_exists($this, $type) ? $this->$type() : $this->missingTypeMethod();
    }

    public function string()
    {
        return 'Text::make('.$this->name.')';
    }

    public function simple_array()
    {
        return 'Code::make('.$this->name.')';
    }

    public function text()
    {
        return 'Text::make('.$this->name.')';
    }

    public function geometry()
    {
        return 'Text::make('.$this->name.')';
    }

    public function boolean()
    {
        return 'Boolean::make('.$this->name.')';
    }

    public function smallint()
    {
        return 'Number::make('.$this->name.')';
    }

    public function integer()
    {
        return 'Number::make('.$this->name.')';
    }

    public function bigint()
    {
        return 'Number::make('.$this->name.')';
    }

    public function blob()
    {
        return '';
    }

    public function enum()
    {
        return 'Text::make('.$this->name.')';
    }

    public function datetime()
    {
        return 'DateTime::make('.$this->name.')';
    }

    public function date()
    {
        return 'Date::make('.$this->name.')';
    }

    public function float()
    {
        return 'Number::make('.$this->name.')';
    }

    public function decimal()
    {
        return 'Number::make('.$this->name.')';
    }

    private function missingTypeMethod()
    {
        dd('Missing a nova generator for '.$this->column['type']);

        return '$faker->word';
    }
}
