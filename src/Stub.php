<?php


namespace Laravelizer;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Stub
{
    protected $assign = ['php_open' => '<?php'];
    protected $columns;

    public function __construct()
    {

    }
    public function model($path): string
    {
        $this->assign['casts'] = [];
        $this->assign['attributes'] = [];
        $path = str_replace(app_path(), '', $path);
        $this->assign['model_namespace'] = $this->getNamespaceFromPath(substr($path, 0, strrpos( $path, '/')), 'App');
        $this->assign['model_name'] = $this->getClassNameFromPath($path);
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
        $this->assign['class_name'] = 'Create' . Str::studly($this->assign['table']) . 'Table';
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
        $this->assign['columns'] = $this->columns;
        $this->assign['timestamps'] = $updated_at && $created_at;
        $this->assign['soft_deletes'] = $soft_deletes;

        return $this->build('migration');
    }

    public function setTable($table): void
    {
        $this->assign['table'] = $table;
    }

    public function setConnection($connection): void
    {
        $this->assign['connection'] = $connection;
    }

    public function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    public function share(): void
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

    protected function getNamespaceFromPath($path, $root = null): string
    {
        $ns = is_null($root) ? [] : [$root];
        foreach (explode('/', $path) as $k) {
            array_push($ns, $k);
        }
        return trim(join('\\', $ns), '\\');
    }

    public function getClassNameFromPath($path): string
    {
        return trim(current(explode('.', last(explode('/', $path)))), '\\');
    }
}