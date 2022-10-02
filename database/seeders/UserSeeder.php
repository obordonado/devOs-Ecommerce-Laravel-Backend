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
                'name' => 'Óscar',
                'surname' => 'Fernández',
                'phone_number' => '632578412',
                'email' => 'oscar@oscar.com',
                'password' => Hash::make('123456'),
                'payment_type' => 'bizum',
                'address' => 'Calle luna 32'
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'César',
                'surname' => 'Fernández',
                'phone_number' => '655278416',
                'email' => 'cesar@cesar.com',
                'password' => Hash::make('123456'),
                'payment_type' => 'paypal',
                'address' => 'Calle mayor 132'
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'Ángela',
                'surname' => 'Beltrán',
                'phone_number' => '633287410',
                'email' => 'angela@angela.com',
                'password' => Hash::make('123456'),
                'payment_type' => 'paypal',
                'address' => 'Calle brunete 1'
            ]
        );

        DB::table('users')->insert(
            [
                'name' => 'Daniela',
                'surname' => 'Martínez',
                'phone_number' => '625571555',
                'email' => 'daniela@daniela.com',
                'password' => Hash::make('123456'),
                'payment_type' => 'bizum',
                'address' => 'Calle princesas 47'
            ]
        );
    }
}