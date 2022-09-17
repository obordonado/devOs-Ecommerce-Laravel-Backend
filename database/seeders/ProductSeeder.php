<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('products')->insert(
            [
                'user_id' => 1,
                'name' => 'Helado chocolate',
                'img_url' => 'https://helados.pro/wp-content/uploads/2018/11/Portada_helado_chocolate.jpg',
                'price' => 3.50,
            ]
        );

        DB::table('products')->insert(
            [
                'user_id' => 1,
                'name' => 'Helado fresa',
                'img_url' => 'https://assets.unileversolutions.com/recipes-v2/231124.jpg?imwidth=800',
                'price' => 3.50,
            ]
        );


        DB::table('products')->insert(
            [
                'user_id' => 1,
                'name' => 'Helado vainilla',
                'img_url' => 'https://recetinas.com/wp-content/uploads/2018/08/helado-de-vainilla.jpg',
                'price' => 3.50,
            ]
        );
    }
}
