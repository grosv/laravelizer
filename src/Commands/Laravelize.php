<?php

namespace Laravelizer\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravelizer\Database;

class Laravelize extends Command
{

    protected $signature = 'laravelize
                            {table=___*___}
                            {--connection=}
                            {--force}
    ';

    protected $tables = [];
    protected $class_name;
    protected $components = ['migration', 'model', 'factory', 'nova', 'test'];
    protected $connection;
    protected $force;
    protected $written;


    public function __construct()
    {
        parent::__construct();
        $this->written = collect([]);

    }

    public function handle()
    {

        $this->connection = $this->hasOption('connection') && !empty($this->option('connection')) ? $this->option('connection') : config('database.default');
        $this->force = $this->hasOption('force') && !empty($this->option('force'));
        $this->tables = $this->argument('table') === '___*___' ? $this->getAllTableNames() : [$this->argument('table')];


        foreach ($this->tables as $table) {
            $this->laravelize($table);
        }
    }

    public function laravelize($table)
    {
        $this->class_name = $this->ask('What do you want to call the model we are creating from table ' . $table . '?', Str::singular(Str::studly($table)));
        foreach ($this->components as $component) {
            if (config('laravelizer.'.$component.'.suppress')) {
                $this->line('<fg=yellow;options=bold>'.ucfirst($component).' Skipped: </>' . $this->getComponentPath($component, $table));
                continue;
            }
            if (file_exists($this->getComponentPath($component, $table)) && !$this->force) {
                $this->line('<fg-red;options-bold>'.ucfirst($component).' Already Exists: ' . $this->getComponentPath($component, $table));
                continue;
            }
            $this->line('<fg=green;options=bold>'.ucfirst($component).' Written: </>' . $this->getComponentPath($component, $table));
            $this->written->push($this->getComponentPath($component, $table));

        }

        dd($this->written);
    }



    protected function getAllTableNames()
    {
        $db = new Database($this->connection);

        return $db->getTables();
    }

    protected function getComponentPath($component, $table)
    {
        return [
            'model' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'migration' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.date('Y_m_d_Hms').'_create_'.$table.'_table.php',
            'factory' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'Factory.php',
            'nova' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'test' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'Test.php',
        ][$component];
    }
}