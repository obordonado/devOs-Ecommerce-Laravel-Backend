<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function createSale(Request $request)
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id ' . $userId . ' is purchasing a product..');

            $validator = Validator::make($request->all(),
            [
                'user_id' => ['required', 'integer'],
                'total_price' => ['required', 'integer'],
                'rating' => ['required', 'integer'],
                'status' => ['required', 'string'],                
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

            $user_id = $request->input('user_id');
            $total_price = $request->input('total_price');
            $rating = $request->input('rating');
            $status = $request->input('status');
            
            $sale = new Sale();
            $sale->user_id = $user_id;
            $sale->total_price = $total_price;
            $sale->rating = $rating;
            $sale->status = $status;
            
            $sale->save();

            Log::info('User id '.$userId.' purchased a total of '.$total_price.' € correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' purchased a total of '.$total_price.'€ correctly.'
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

    public function getOwnPurchases($id) 
    {
        try {
            $userId = auth()->user()->id;

            $sales = User::query()->find($userId)->sales;

            Log::info('User id '.$userId.' recovered past purchases correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => $sales
                ],
                200
            );
            
        } catch (\Exception $exception) {
            Log::info('Error getting own purchases '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'User id '.$userId.' failed to get past purchases.'
                ],
                400
                );
        }
    }
}