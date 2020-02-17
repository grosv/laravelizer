<?php

namespace Laravelizer;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Stub
{
    public $assign;
    public $columns;

    public function __construct()
    {
        $this->assign = [
            'php_open'         => '<?php',
            'casts'            => [],
            'attributes'       => [],
            'add_soft_deletes' => false,
            'add_timestamps'   => false,
            'soft_deletes'     => false,
            'timestamps'       => false,
            'connection'       => '',
            'table'            => '',
        ];
    }

    public function model($path): string
    {
        $this->assign['soft_delete'] = isset($this->columns['deleted_at']);
        $this->assign['timestamps'] = isset($this->columns['created_at']) && isset($this->columns['updated_at']);

        foreach ($this->columns as $k => $v) {
            if ($v['type'] == 'json') {
                $this->assign['casts'][$k] = 'array';
                $this->assign['attributes'][$k] = '{}';
            }
        }

        return $this->build('model');
    }

    public function migration($path): string
    {
        $this->assign['class_name'] = 'Create'.Str::studly($this->assign['table']).'Table';
        $created_at = $updated_at = $soft_deletes = false;
        foreach ($this->columns as $column) {
            if ($column['name'] == 'deleted_at') {
                $soft_deletes = true;
            }
            if ($column['name'] == 'created_at') {
                $created_at = true;
            }
            if ($column['name'] == 'updated_at') {
                $updated_at = true;
            }
        }

        $this->assign['timestamps'] = $updated_at && $created_at;

        return $this->build('migration');
    }

    public function factory($path): string
    {
        return $this->build('factory');
    }

    public function nova($path): string
    {
        return $this->build('nova');
    }

    public function setTable($table): void
    {
        $this->assign['table'] = $table;
    }

    public function setConnection($connection): void
    {
        $this->assign['connection'] = $connection;
    }

    public function setModelClassName(string $value)
    {
        $this->assign['model_name'] = $value;
    }

    public function setModelNamespace(string $value)
    {
        $this->assign['model_namespace'] = $value;
    }

    public function setSoftDeletes(bool $value)
    {
        $this->assign['soft_deletes'] = $value;
    }

    public function setColumns(Collection $columns): void
    {
        $this->columns = $columns;
        $this->assign['columns'] = $this->columns;
    }

    public function setOptions($options)
    {
        foreach ($options as $k => $v) {
            if (in_array($k, ['updated_at', 'created_at', 'add_timestamps', 'add_deletes'])) {
                $this->assign[$k] = $v;
            }
        }
    }

    private function share(): void
    {
        foreach ($this->assign as $k => $v) {
            View::share($k, $v);
        }
    }

    public function build($component): string
    {
        $this->share();

        return view(config('laravelizer.'.$component.'.stub'));
    }
}
