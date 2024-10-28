<?php

namespace Webkul\Installer\Database\Seeders\Person;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('person_statuses')->delete();

        $statuses = [
            [
                'name'       => 'Notification',
                'sort_order' => 1,
            ],
            [
                'name'       => 'Investigation',
                'sort_order' => 2,
            ],
            [
                'name'       => 'Examination',
                'sort_order' => 3,
            ],
            [
                'name'       => 'Analysis',
                'sort_order' => 4,
            ],
            [
                'name'       => 'Conclusion',
                'sort_order' => 5,
            ],
        ];

        DB::table('person_statuses')->insert($statuses);
    }
}
