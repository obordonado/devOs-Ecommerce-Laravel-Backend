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
                'brand' => 'Special Creams',
                'name' => 'Chocolate',
                'img_url' => 'https://helados.pro/wp-content/uploads/2018/11/Portada_helado_chocolate.jpg',
                'price' => 3.50,
            ]
        );

        DB::table('products')->insert(
            [
                'user_id' => 1,
                'brand' => 'Special Creams',
                'name' => 'Strawberry',
                'img_url' => 'https://assets.unileversolutions.com/recipes-v2/231124.jpg?imwidth=800',
                'price' => 3.70,
            ]
        );


        DB::table('products')->insert(
            [
                'user_id' => 1,
                'brand' => 'Special Creams',
                'name' => 'Vanilla',
                'img_url' => 'https://recetinas.com/wp-content/uploads/2018/08/helado-de-vainilla.jpg',
                'price' => 3.10,
            ]
        );

        DB::table('products')->insert(
            [
                'user_id' => 1,
                'brand' => 'Sorted Creams',
                'name' => 'Fruit',
                'img_url' => 'http://www.recetario-cocina.com/archivosbd/helados-caseros-de-frutas.jpg',
                'price' => 3.60,
            ]
        );

        DB::table('products')->insert(
            [
                'user_id' => 1,
                'brand' => 'Sorted Creams',
                'name' => 'Fruit',
                'img_url' => 'https://cdn.statically.io/img/dulcesdiabeticos.com/wp-content/uploads/2017/06/helados-de-frutas-saludables-sin-azucar_opt-1.jpg?quality=100&f=auto',
                'price' => 3.50,
            ]
        );
    }
}
