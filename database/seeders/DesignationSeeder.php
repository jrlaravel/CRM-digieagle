<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DesignationSeeder extends Seeder
{
    public function run(): void
    {
         $departments = DB::table('department')->pluck('id', 'name');

         $designations = [
             ['name' => 'HR', 'department_id' => $departments['HR'], 'status' => 1],
             ['name' => 'Social Media Executive', 'department_id' => $departments['Digital Marketing'], 'status' => 1],
             ['name' => 'Graphic Designer', 'department_id' => $departments['Designing Dep.'], 'status' => 1],
             ['name' => 'ui/ux designer', 'department_id' => $departments['Designing Dep.'], 'status' => 1],
             ['name' => 'SEO Executive', 'department_id' => $departments['Digital Marketing'], 'status' => 1],
             ['name' => 'Motion Graphic Designer', 'department_id' => $departments['Designing Dep.'], 'status' => 1],
             ['name' => 'jr. laravel developer', 'department_id' => $departments['Technical Dep.'], 'status' => 1],
             ['name' => 'Social Media Executive', 'department_id' => $departments['Digital Marketing'], 'status' => 1],
         ];
 
         DB::table('designation')->insert($designations);
    }
}
