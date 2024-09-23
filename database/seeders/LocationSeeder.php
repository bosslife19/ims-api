<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->truncate();

        DB::table('locations')->insert([
            [
                "title" => "school",
                "slug" => "school",
            ],
            [
                "title" => "warehouse",
                "slug" => "warehouse",
            ],
            [
                "title" => "ec office",
                "slug" => "ec-office",
            ],
            [
                "title" => "lga office",
                "slug" => "lga-office",
            ],
            [
                "title" => "subeb office",
                "slug" => "subeb-office",
            ]
        ]);
    }
}
