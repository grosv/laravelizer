<?php

namespace Laravelizer\Actions;

use Doctrine\DBAL\Connection;
use Illuminate\Support\Facades\DB;

class Autopilot
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function execute()
    {


        foreach (DB::connection($this->connection)->getDoctrineSchemaManager()->listTables() as $table) {

        }
    }
}