<?php


namespace Laravelizer;

use ColumnClassifier\Classifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Factory
{

    protected $column;
    protected $name;
    protected $sample;

    protected $classified = [
        'first_name' => 'firstName',
        'last_name' => 'lastName',
        'full_name' => 'name',
        'phone' => 'phoneNumber',
        'email' => 'email',
        'city' => 'city',
        'state' => 'state',
        'state_abbr' => 'stateAbbr',
        'zip_code' => 'zipcode',
        'country' => 'country',
        'country_code' => 'countryCode',
        'currency' => 'currencyCode',
        'company' => 'companyName',
        'job_title' => 'jobTitle',
        'sentence' => 'sentence',
        'paragraph' => 'paragraph',
        'html' => 'html',
        'word' => 'word',


    ];

    public function __construct($column)
    {
        $this->column = $column;
        $this->name = strtolower($column['name']);
    }

    public function execute()
    {
        $this->sample = DB::connection($this->column['connection'])
            ->table($this->column['table'])
            ->select($this->column['name'])
            ->groupBy($this->column['name'])
           // ->limit(100)->get()
            ->pluck($this->column['name']);

        $type = $this->column['type'];

        return method_exists($this, $type) ? $this->$type() : $this->missingTypeMethod();


    }

    public function string()
    {
        if ($this->name === 'deleted_at') {
            return '';
        }
        if (Str::contains($this->name, 'email')) {
            return '$faker->email';
        }
        if (Str::containsAll($this->name, ['first', 'name'])) {
            return '$faker->firstName';
        }
        if (Str::containsAll($this->name, ['last', 'name'])) {
            return '$faker->lastName';
        }
        if (Str::contains($this->name, ['username', 'uname', 'nickname'])) {
            return '$faker->userName';
        }
        if (Str::contains($this->name, ['website', 'domain'])) {
            return '$faker->domainName';
        }
        if (Str::contains($this->name, ['url'])) {
            return '$faker->url';
        }
        if (Str::contains($this->name, ['password'])) {
            return '$faker->password';
        }
        if (Str::contains($this->name, ['address'])) {
            return '$faker->address';
        }

        $classifier = new Classifier($this->sample);

        return '$faker->' . $this->classified[(string)$classifier->execute()];

    }

    public function simple_array()
    {
        return 'join(",", $faker->words(10))';
    }

    public function text()
    {
        return '$faker->paragraph()';
    }

    public function geometry()
    {
        return 'ST_GeomFromText(\'POINT(1 1)\')';
    }

    public function boolean()
    {
        return '$faker->boolean()';
    }

    public function smallint()
    {
        return '$faker->numberBetween(' . min($this->sample->toArray()) . ', ' . max($this->sample->toArray()) . ')';
    }

    public function integer()
    {
        return '$faker->numberBetween(' . min($this->sample->toArray()) . ', ' . max($this->sample->toArray()) . ')';
    }

    public function bigint()
    {
        return '$faker->numberBetween(' . min($this->sample->toArray()) . ', ' . max($this->sample->toArray()) . ')';
    }

    public function blob()
    {
        return '';
    }

    public function enum()
    {
        $array = join('","', $this->sample->toArray());
        return '$faker->randomElement([" ' . $array . '"])';
    }

    public function datetime()
    {
        return '$faker->dateTime()';
    }

    public function date()
    {
        return '$faker->date()';
    }

    public function float()
    {
        $exp = $this->column['scale'] - $this->column['precision'];
        $max = pow(10, $exp) - 1;

        return '$faker->randomFloat(' . $this->column['scale'] . ', 0, ' . $max . ')';
    }

    public function decimal()
    {
        $exp = $this->column['precision'] - $this->column['scale'];
        $max = pow(10, $exp) - 1;

        return '$faker->randomFloat(' . $this->column['scale'] . ', 0, ' . $max . ')';
    }

    private function missingTypeMethod()
    {
        dump($this->sample);
        dd('Missing a faker generator for ' . $this->column['type']);

        return '$faker->word';
    }


}