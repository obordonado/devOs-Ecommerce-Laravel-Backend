<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert(
            [
                'title' => 'Resident Evil Village',
                'user_id' => '1'
            ]);

        DB::table('games')->insert(
            [
                'title' => 'System Shock 2',
                'user_id' => '2'
            ]);

        DB::table('games')->insert(
            [
                'title' => 'WarHammer 40.000',
                'user_id' => '3'
            ]);
        
        DB::table('games')->insert(
            [
                'title' => 'Phasmophobia',
                'user_id' => '4'
            ]);
    }
}
