<?php

namespace Laravelizer\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravelizer\Actions\Autopilot;
use Laravelizer\Database;

class Laravelize extends Command
{

    protected $signature = 'laravelize:table
                            {table}
                            {connection?}
                            {--force}
                            {--silent}
    ';

    protected $tables = [];
    protected $class_name;
    protected $components = ['migration', 'model', 'factory', 'nova', 'test'];


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->tables = $this->argument('table') === '*' ? $this->getAllTableNames() : [$this->argument('table')];

        foreach ($this->tables as $table) {
            $this->laravelize($table);
        }
    }

    public function laravelize($table)
    {
        foreach ($this->components as $component) {
            if (config('laravelize.'.$component.'.suppress')) {
                $this->line('<fg-color:yellow>Skipping ' . $component . '</fg-color> - Suppression is set to true for ' . $component . ' in your configuration.');
                continue;
            }

            $this->class_name = $this->ask('What do you want to call the model we are creating from table ' . $table . '?', Str::singular(Str::studly($table)));

            $this->explain($table);

            if (!$this->confirm('🤔 If this looks good to you, say yes and we will create your files. Or say no and abandon the mission.')) {
                $this->line('🐔 You have surrendered.');
            }

        }
    }

    public function explain($table)
    {
        foreach ($this->components as $component)
        {
            $this->line('First we will closely inspect your database table and see what kind of stuff you keep in ' . $table);
            $this->line('Then we will create the files you need to use this table in your Laravel app.');
            $this->line('Here are the specific things we will create: ');
            if (! config('laravelizer.'.$component.'.suppress')) {
                $this->line('✏️ We will write a ' . $component . ' at ' . $this->getComponentPath($component, $table));
            }
        }
    }

    protected function getAllTableNames()
    {
        $db = new Database($this->argument('connection'));

        return $db->getTables();
    }

    protected function getComponentPath($component, $table)
    {
        return [
            'model' => config('laravelizer.'.$component.'.path.').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'migration' => config('laravelizer.'.$component.'.path.').DIRECTORY_SEPARATOR.date('Y_m_d_Hms').'create_'.$table.'_table.php',
            'factory' => config('laravelizer.'.$component.'.path.').DIRECTORY_SEPARATOR.$this->class_name.'Factory.php',
            'nova' => config('laravelizer.'.$component.'.path.').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'test' => config('laravelizer.'.$component.'.path.').DIRECTORY_SEPARATOR.'Test'.$this->class_name.'Schema.php',
        ][$component];
    }
}