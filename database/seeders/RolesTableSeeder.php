<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("roles")->insert([
            [
                "name" => "QA",
                "slug" => "qa",
                "description" => "QA Role",
            ],
            [
                "name" => "Admin",
                "slug" => "admin",
                "description" => "Admin Role",
            ],
            [
                "name" => "Warehouse Staff",
                "slug" => "warehouse-staff",
                "description" => "Warehouse Staff Role",
            ],
            [
                "name" => "Head Teacher",
                "slug" => "head-teacher",
                "description" => "Head Teacher Role",
            ],
        ]);
    }
}
