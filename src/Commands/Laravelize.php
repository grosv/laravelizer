<?php

namespace Laravelizer\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravelizer\Database;
use Laravelizer\Filesystem;
use Laravelizer\Stub;
use PhpSchool\CliMenu\CliMenu;

class Laravelize extends Command
{
    protected $signature = 'laravelize
                            {table=___*___}
                            {--connection=}
                            {--updated_at=}
                            {--created_at=}
                            {--add_timestamps}
                            {--add_deletes}
    ';

    protected $tables = [];
    protected $class_name;
    protected $components = ['migration', 'model', 'factory', 'nova', 'test'];
    protected $connection;
    protected $force;
    protected $written;
    protected $skipped;
    protected $config;

    protected $colors = [
        'normal' => ['fg_color' => 'green', 'bg_color' => 'black'],
        'warning' => ['fg_color' => 'black', 'bg_color' => 'yellow'],
        'error' => ['fg_color' => 'black', 'bg_color' => 'red'],
    ];
    protected $fg_color = 'green';
    protected $bg_color = 'black';

    public function __construct()
    {
        parent::__construct();
        $this->written = collect([]);
        $this->skipped = collect([]);
        $this->config = config('laravelizer');
    }

    public function handle()
    {
        $this->connection = $this->hasOption('connection') && !empty($this->option('connection')) ? $this->option('connection') : config('database.default');
        $this->force = $this->hasOption('force') && !empty($this->option('force'));
        $this->tables = $this->argument('table') === '___*___' ? $this->getAllTableNames() : [$this->argument('table')];

        $useconfig = $this->menu('Welcome to Laravelizer!')
            ->setForegroundColour($this->colors['normal']['fg_color'])
            ->setBackgroundColour($this->colors['normal']['bg_color'])
            ->addStaticItem('We will now walk you through getting ' . $this->connection. ' set up for Laravel.')
            ->addLineBreak(' ', 2)
            ->addStaticItem('Use your keyboard because your mouse will not work here.')
            ->addLineBreak(' ', 2)
            ->addOption('default', 'Just use the really well thought out default settings (Recommended)')
            ->addOption('custom', 'Customize settings')
            ->disableDefaultItems()
            ->open();

        $callable = function (CliMenu $menu) {
            $this->config[Str::singular(strtolower($menu->getSelectedItem()->getText()))]['build'] = !$this->config[Str::singular(strtolower($menu->getSelectedItem()->getText()))]['build'];
        };

        $this->menu('What type of components do you want to build?')
            ->setForegroundColour($this->colors['normal']['fg_color'])
            ->setBackgroundColour($this->colors['normal']['bg_color'])
            ->addStaticItem('Models (Required)')
            ->addCheckboxItem('Migrations', $callable)
            ->addCheckboxItem('Factories', $callable)
            ->addCheckboxItem('Nova', $callable)
            ->addCheckboxItem('Tests', $callable)
            ->addLineBreak(' ', 2)
            ->setExitButtonText('Start Laravelizing!')
            ->open();


        foreach ($this->tables as $table) {
            if ($table !== 'migrations') {

                $this->laravelize($table);
            }
        }

        $this->info('All done!');
    }

    public function laravelize($table)
    {
        $this->class_name = $this->menu('What do you want to call the model for table ' . $table . '?')
            ->setForegroundColour($this->colors['normal']['fg_color'])
            ->setBackgroundColour($this->colors['normal']['bg_color'])
            ->addOption(Str::studly(Str::singular($table)), Str::studly(Str::singular($table)))
            ->addQuestion('Something Else', 'Model Name')
            ->disableDefaultItems()
            ->open();

        foreach ($this->components as $component) {
            if (!$this->config[$component]['build']) {
                $this->written->push([
                    'table' => $table,
                    'component' => ucfirst($component),
                    'path' =>  $this->getComponentPath($component, $table),
                    'result' => 'Skipped'
                ]);
                continue;
            }
            if (file_exists($this->getComponentPath($component, $table)) && !$this->force) {
                $force = $this->menu('File Already Exists!')
                    ->setForegroundColour($this->colors['warning']['fg_color'])
                    ->setBackgroundColour($this->colors['warning']['bg_color'])
                    ->addStaticItem(ucfirst($component) . ' already exists at ' . $this->getComponentPath($component, $table))
                    ->addLineBreak(' ', 2)
                    ->addOption('skip', 'Skip')
                    ->addOption('force', 'Replace')
                    ->disableDefaultItems()
                    ->open();

                if ($force === 'skip') {
                    $this->written->push([
                        'table' => $table,
                        'component' => ucfirst($component),
                        'path' =>  $this->getComponentPath($component, $table),
                        'result' => 'Skipped'
                    ]);
                    continue;
                }
            }
            $this->written->push([
                'table' => $table,
                'component' => ucfirst($component),
                'path' =>  $this->getComponentPath($component, $table),
                'result' => 'Written'
            ]);

            $stub = new Stub();
            $db = new Database($this->connection);

            $stub->setTable($table);
            $stub->setConnection($this->connection);
            $stub->setColumns($db->getColumns($table));
            $stub->setOptions($this->option());
            $stub->setModelClassName($this->class_name);
            $stub->setModelNamespace($this->getNamespaceFromPath($this->getComponentPath('model', $table)));
            $stub->setSoftDeletes($stub->columns->contains('deleted_at'));

            $fs = new Filesystem();
            $fs->write($this->getComponentPath($component, $table), $stub->$component($this->getComponentPath($component, $table)));

        }
    }



    protected function getAllTableNames()
    {
        $db = new Database($this->connection);

        return $db->getTables();
    }

    protected function getComponentPaths($component, $table)
    {
        return [
            'model'     => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'migration' => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.date('Y_m_d_Hms').'_create_'.$table.'_table.php',
            'factory'   => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'Factory.php',
            'nova'      => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'.php',
            'test'      => config('laravelizer.'.$component.'.path').DIRECTORY_SEPARATOR.$this->class_name.'Test.php',
        ];
    }



    protected function getComponentPath($component, $table)
    {
        return $this->getComponentPaths($component, $table)[$component];
    }

    protected function getNamespaceFromPath($path, $root = null): string
    {
        $path = str_replace(base_path(), '', $path);
        $path = substr($path, 0, strrpos($path, '/'));
        $ns = is_null($root) ? [] : [$root];
        foreach (explode('/', $path) as $k) {
            array_push($ns, Str::studly($k));
        }

        return trim(implode('\\', $ns), '\\');
    }
}
