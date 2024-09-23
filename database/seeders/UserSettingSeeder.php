<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("user_settings")->truncate();

        $users = User::get();
        foreach ($users as $user) {
            DB::table('user_settings')->insert([
                'user_id' => $user->id,
            ]);
        }
    }
}
