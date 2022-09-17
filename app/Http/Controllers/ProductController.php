<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getAllProducts()
    {
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

    public function getProductsByBrand(Request $request)
    {
        try {            
            Log::info('Getting products by brand...');

            $brand = $request->input('brand');
            $brand = Product::query()->where('brand','=',$brand)->get()->toArray();

            if(!$brand) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Brand does not exist.'
                    ],
                    400
                );
            }
    
            return response()->json(
                [    
                    'success' => true,
                    'message' => 'Products were retrieved by brand correctly.',
                    'data' => $brand
                ]
            );
            Log::info('Products were retrieved by brand correctly.');

        } catch (\Exception $exception) {
            Log::info('Error getting products by brand. '. $exception->getMessage());

            return response()->json(
                [                
                    'success' => false,
                    'message' => 'Failed to get products by brand.'
                ]
            );
        }
    }

    public function getProductsByname(Request $request)
    {
        try {            
            Log::info('Getting products by name...');

            $name = $request->input('name');
            $name = Product::query()->where('name','=',$name)->get()->toArray();

            if(!$name) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Chosen name does not exist.'
                    ],
                    400
                );
            }

            return response()->json(
                [    
                    'success' => true,
                    'message' => 'Products were retrieved by name correctly.',
                    'data' => $name
                ],
                200
        );
            Log::info('Products were retrieved by name correctly.');

        } catch (\Exception $exception) {
            Log::info('Error getting products by name. '. $exception->getMessage());

            return response()->json(
                [                
                    'success' => false,
                    'message' => 'Failed to get products by name.'
                ],
                400
            );
        }
    }

    public function getProductById($id)
    {
        try {            
            Log::info('Getting product by id...');
            $product = Product::findOrFail($id);
            Log::info('Getting product by id worked correctly.');

            return response()->json(
                [
                    'success'=> true,
                    'message' => 'Product retrieved successfully.',
                    'data'=> $product
                ],
                200
            );
    
        } catch (\Exception $exception) {
            Log::error('Getting product by id failed. '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Could not retrieve product by id.'
                ],
                400
            );
        }
    }

    public function createProduct(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id ' . $userId . ' is creating a product..');

            $validator = Validator::make($request->all(),
            [
                'user_id' => ['required', 'integer'],
                'brand' => ['required', 'string'],
                'name' => ['required', 'string'],
                'img_url' => ['required', 'string'],
                'price' => ['required', 'integer'],
            ]);
            Log::info('User id ' . $userId . ' passed validator correctly.');

            if ($validator->fails()) {

                return response()->json(
                    [
                        "success" => false,
                        "message" => 'Error creating new product. ' . $validator->errors()
                    ],
                    400
                );
            };

            $user_id = $request->input('user_id');
            $brand = $request->input('brand');
            $name = $request->input('name');
            $img_url = $request->input('img_url');
            $price = $request->input('price');

            $product = new Product();
            $product->user_id = $user_id;
            $product->brand = $brand;
            $product->name = $name;
            $product->img_url = $img_url;
            $product->price = $price;
            $product->save();

            Log::info('User id '.$userId.' created product '.$brand.' '.$name.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' created product '.$brand.' '.$name.' correctly.'
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::info('Error creating new product '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating product.'
                ],
                400
            );
        }
    }

    public function editProductById(Request $request, $id)
    {        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' updating product...');

            $validator = Validator::make($request->all(),
            [
                'user_id' => ['required','integer'],
                'brand' => ['required','string','min:2','max:45'],
                'name' => ['required','string','min:2','max:45'],
                'img_url' => ['required','string','min:8','max:120'],
                'price' => ['required','integer'],
            ]);

            if($validator->fails()) {
                Log::info('User id '.$userId.' validation error updating product. '.$validator->errors());

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User id '.$userId.' validation error updating product. '.$validator->errors()
                    ],
                    400
                );
            }            
            Log::info('User id '.$userId.' passed validator correctly.');

            $product = Product::query()-> find($id);            

            if (!$product){
                Log::info('Product '.$id.' does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This product does not exist.',
                    ],
                    404
                );
            }

            $user_id = $request->input('user_id');
            $brand = $request->input('brand');
            $name = $request->input('name');
            $img_url = $request->input('img_url');
            $price= $request->input('price');

            if(isset($user_id)){
                $product->user_id = $user_id;
            }

            if(isset($brand)){
                $product->brand = $brand;
            }

            if(isset($name)){
                $product->name = $name;
            }

            if(isset($img_url)){
                $product->img_url = $img_url;
            }

            if(isset($price)){
                $product->price = $price;
            }

            $product->save();
            Log::info('User id '.$userId.' updated product '.$name.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Message ' . $id . ' updated correctly.'
                ],
                200
                );

        } catch (\Exception $exception) {            
            Log::info('Error updating product. ' . $exception->getMessage());
            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error updating product.'
                ],
                400
            );
        }
    }
    
    public function deleteProductById($id)
    {
        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' deleting product...');

            $product = Product::query()-> find($id);            

            if (!$product){
                Log::info('Product id '.$id.' does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This product id does not exist.',
                    ],
                    404
                );
            }

            $product->delete();
            Log::info('User id '.$userId.' deleted product correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Message '.$id.' deleted correctly.',

                ],
                200
                );

        } catch (\Exception $exception) {            
            Log::info('Error deleting product. '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error deleting product.'
                ],
                400
            );
        }
    }

}
