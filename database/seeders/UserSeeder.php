<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name' => 'Dani',
                'email' => "dani@dani.com",
                'password' => Hash::make('123456')
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'Cesar',
                'email' => "cesar@cesar.com",
                'password' => Hash::make('123456')
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'Angela',
                'email' => "angela@angela.com",
                'password' => Hash::make('123456')
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'Daniela',
                'email' => "daniela@daniela.com",
                'password' => Hash::make('123456')
            ]
        );
    }
}