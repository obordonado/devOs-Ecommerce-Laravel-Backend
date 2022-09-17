<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getAllProducts(){
        try {
            Log::info('Getting all products...');

            $products = DB::table('products')
            ->select()
            ->get()
            ->toArray();  
            
           Log::info('All products retrieved correctly.');

            return response()->json(
                    [
                    'success'=> true,
                    'message' => 'Available products retrieved successfully.',
                    'data'=> $products
                    ],
                    200
                );
        
        } catch (\Exception $exception) {

            Log::error('Error getting available products. '.$exception->getMessage());

            return response()->json(
                [
                'success'=> false,
                'message' => 'Available products could not be retrieved.'
                ],
                400
            );
        }
    }

    public function getProductsByBrand(Request $request){

        try {
            
            Log::info('Getting products by brand...');

            $brand = $request->input('brand');

            $brand = Product::query()->where('brand','=',$brand)->get()->toArray();
    
            return response()->json([
    
                'success' => true,
                'message' => 'Products were retrieved by brand correctly.',
                'data' => $brand
            ]);
            
            Log::info('Products were retrieved by brand correctly.');

        } catch (\Exception $exception) {

            Log::info('Error getting products by brand. '. $exception->getMessage());

            return response()->json([
                
                'success' => false,
                'message' => 'Failed to get products by brand.'
            ]);
        }
    }

    public function getProductsByname(Request $request){

        try {
            
            Log::info('Getting products by name...');

            $name = $request->input('name');

            $name = Product::query()->where('name','=',$name)->get()->toArray();
    
            return response()->json([
    
                'success' => true,
                'message' => 'Products were retrieved by name correctly.',
                'data' => $name
            ]);
            
            Log::info('Products were retrieved by brand correctly.');

        } catch (\Exception $exception) {

            Log::info('Error getting products by name. '. $exception->getMessage());

            return response()->json([
                
                'success' => false,
                'message' => 'Failed to get products by name.'
            ]);
        }
    }


}
