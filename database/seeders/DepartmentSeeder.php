<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'HR', 'status' => 1],
            ['name' => 'Digital Marketing', 'status' => 1],
            ['name' => 'Designing Dep.', 'status' => 1],
            ['name' => 'Digital Marketing', 'status' => 1],
            ['name' => 'Technical Dep.', 'status' => 1],
        ];

        DB::table('department')->insert($departments);
    }
}
