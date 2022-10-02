<?php

namespace App\Http\Controllers;

use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ProductSaleController extends Controller
{
    public function createProductSale(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id ' . $userId . ' is purchasing a product..');

            $validator = Validator::make($request->all(),
            [
                'sale_id' => ['required', 'integer'],
                'product_id' => ['required', 'integer'],
                'quantity' => ['required', 'integer'],
                'price' => ['required'],
            ]);
            Log::info('User id ' . $userId . ' passed validator correctly.');

            if ($validator->fails()) {

                return response()->json(
                    [
                        "success" => false,
                        "message" => 'Error purchasing product. ' . $validator->errors()
                    ],
                    400
                );
            };

            $sale_id = $request->input('sale_id');
            $product_id = $request->input('product_id');
            $quantity = $request->input('quantity');
            $price = $request->input('price');
            
            $sale = new ProductSale();
            $sale->sale_id = $sale_id;
            $sale->product_id = $product_id;
            $sale->quantity = $quantity;
            $sale->price = $price;            
            $sale->save();

            Log::info('User id '.$userId.' purchased product_id '.$product_id.', quantity '.$quantity.', at price '.$price. ' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' purchased product_id '.$product_id.', quantity '.$quantity.', at price '.$price. ' correctly.'
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

    // public function getOwnPurchases() 
    // {
    //     try {
    //         $userId = auth()->user()->id;
    //         $sales = User::query()->find($userId)->sales;
    //         Log::info('User id '.$userId.' recovered past purchases correctly.');

    //         return response()->json(
    //             [
    //                 'success' => true,
    //                 'message' => $sales
    //             ],
    //             200
    //         );
    //     } catch (\Exception $exception) {
    //         Log::info('Error getting own purchases '.$exception->getMessage());

    //         return response()->json(
    //             [
    //                 'success' => false,
    //                 'message' => 'User id '.$userId.' failed to get past purchases.'
    //             ],
    //             400
    //         );
    //     }
    // }

    public function getOwnPurchasesById($id)
    {
        try {        
            $userId = auth()->user()->id;   
            Log::info('User id '.$userId.' getting purchase in product_sale by id...');

            $purchase = Sale::query()->where('id','=',$id)->get()->toArray();

            if(!$purchase){
                return response()->json(
                    [
                        'success'=> false,
                        'message'=> 'Purchase id does not exist.'
                    ],
                    400
                );
            }Log::info('User id '.$userId.' retrieved purchase by id '.$id.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$id.' recovered purchase correctly.' ,
                    'purchase' => $purchase
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::info('Error getting purchase by Id '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'User id '.$userId.' failed to get purchase by Id.'
                ],
                400
            );
        }
    }

    public function deletePurchaseById($id)
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id '.$userId. ' deleting purchase...');
            $purchase = Sale::query()->where('user_id','=',$userId)->where('id','=',$id)->get();

            if(!$purchase){

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'The purchase id does not exist.'
                    ],
                    400
                );
            }Log::info('User id '.$userId.' deleted purchase id '.$id.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Purchase id '.$id.' deleted correctly by user id '.$userId
                ],
                200
            );          
        } catch (\Exception $exception) {
            Log::info('Error getting purchase by Id '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'User id '.$userId.' failed to get purchase by Id.'
                ],
                400
            );        
        }
    }
    public function getAllSalesBySuperAdmin()
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id '.$userId.' trying to get all sales...');
            $sales = Sale::query()->get()->toArray();

            if(!$sales){

                return response()->json(
                    [
                        'success'=> true,
                        'message'=> 'There are no sales yet.'
                    ],
                    200
                );
            }Log::info('User id '.$userId.' retrieved all sales correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Retrieved all sales correctly.',
                    'data' => $sales
                ],
                200
            );            
        } catch (\Exception $exception) {
            Log::info('Error getting all users. '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Page not found.'
                ],
                404
            );        
        }
    }

    public function getSaleByUserId(Request $request, $id)
    {
        try {
            $userId=auth()->user()->id;
            Log::info('User id '.$userId.' getting all purchases by user id...');

            $purchase = Sale::query()->where('user_id','=',$id)->get()->toArray();
            
            if(!$purchase){
                return response()->json(
                    [
                        'success'=> false,
                        'message' => 'Not found.'
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' retrieved all purchases of user id '.$id.' correctly.',
                    'data' => $purchase
                ],
                200
            );
    }catch (\Exception $exception) {
            Log::info('Error getting purchase by email. '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting purchase by email.'
                ],
                500
            );        
        }
    }
    public function getSaleById(Request $request, $id)
    {
        try {
            $userId=auth()->user()->id;
            Log::info('User id '.$userId.' getting purchase by id...');

            $purchase = Sale::query()->where('id','=',$id)->get()->toArray();
            
            if(!$purchase){
                return response()->json(
                    [
                        'success'=> false,
                        'message' => 'Not found.'
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' retrieved purchase by id '.$id.' correctly.',
                    'data' => $purchase
                ],
                200
            );
    }catch (\Exception $exception) {
            Log::info('Error getting purchase by id. '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting purchase by id.'
                ],
                404
            );        
        }
    }

    
}

