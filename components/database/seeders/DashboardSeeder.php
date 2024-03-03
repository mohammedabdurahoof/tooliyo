<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Date;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          History::create([
            "id"         => 1,
            "tool_name"  => "Find Facebook ID",
            "client_ip"  => "127.0.0.1",
            "country"    => null,
            "flag"       => null,
            "updated_at" => Date::now(),
            "created_at" => Date::now()
          ]);
    }
}
