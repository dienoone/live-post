<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    protected function truncate(string $table)
    {
        DB::table($table)->truncate();
    }
}
