<?php


namespace Laravelizer;


use Illuminate\Support\Collection;
use Laravelizer\Models\City;
use Laravelizer\Models\Currency;
use Laravelizer\Models\FirstName;
use Laravelizer\Models\JobTitle;
use Laravelizer\Models\USState;

class Identifier
{
    protected $data;
    protected $results;
    protected $sentence_regex = '/(?<=[.!?]|[.!?][\'"])\s+(?=\S)/';

    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->results = [
            'first_name' => 0,
            'last_name' => 0,
            'full_name' => 0,
            'phone' => 0,
            'email' => 0,
            'city' => 0,
            'state' => 0,
            'state_abbr' => 0,
            'zip_code' => 0,
            'country' => 0,
            'country_code' => 0,
            'currency' => 0,
            'company' => 0,
            'job_title' => 0,
            'sentence' => 0,
            'paragraph' => 0,
            'html' => 0
        ];

    }

    public function execute()
    {
        $this->data->each(function($row) {
            foreach ($this->results as $k) {
                $this->results[$k] += $this->$k($row);
            }
        });
        $this->results['word'] = (int)$this->data->count() / 2;
        asort($this->results);
        return array_pop(array_keys($this->results));

    }

    private function first_name($row)
    {
        return FirstName::where('name', strtolower(strtolower(trim($row))))->count();
    }

    public function last_name($row)
    {
        return 0;
    }

    private function full_name($row)
    {
        $parts = explode(' ', $row);
        if (sizeof($parts) < 2 || sizeof($parts) > 4) {
            return 0;
        }
        foreach ($parts as $part) {
            if (FirstName::where('name', strtolower(strtolower(trim($part))))->count() > 0) {
                return 1;
            }
        }
        return 0;
    }

    public function phone($row)
    {
        return (int)strlen(preg_replace(['^0-9'], '', $row)) === 10;
    }

    public function email($row)
    {
        return (int)filter_var(trim($row), FILTER_VALIDATE_EMAIL);
    }

    public function city($row)
    {
        return (int)City::where('city', strtolower(trim($row)))->count() > 0;
    }

    public function state($row)
    {
        return USState::where('state', strtolower(trim($row)))->count();
    }

    public function state_abbr($row)
    {
        return USState::where('abbr', strtolower(trim($row)))->count();
    }

    public function zip_code($row)
    {
        return (int)is_numeric(trim($row) && strlen(trim($row)) === 5);
    }

    public function currency($row)
    {
        return Currency::where('code', strtoupper(trim($row)))->count();
    }

    public function company($row)
    {
        if (sizeof(explode(' ', $row)) > 3) {
            return 0;
        }
        if (ucwords($row) != $row) {
            return 0;
        }
        return 1;
    }

    public function job_title($row)
    {
        return JobTitle::where('title', strtolower($row))->count();
    }

    public function sentence($row)
    {
        return sizeof(preg_split($this->sentence_regex, $row, -1, PREG_SPLIT_NO_EMPTY)) === 1;
    }

    public function paragraph($row)
    {
        return sizeof(preg_split($this->sentence_regex, $row, -1, PREG_SPLIT_NO_EMPTY)) > 1;
    }

    public function html($row)
    {
        return (int)$row != strip_tags($row);
    }

}