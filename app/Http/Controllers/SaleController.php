<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function createSale(Request $request) ///create sale by id
    {
        try {
            $userId = auth()->user()->id;
            Log::info('User id ' . $userId . ' is purchasing a product..');

            // $validator = Validator::make($request->all(),
            // [
            //     'user_id' => ['required', 'integer'],
            //     'total_price' => ['required', 'integer'],
            //     'rating' => ['required', 'integer'],                
            //     'status' => ['required', 'string']
            // ]);
            // Log::info('User id ' . $userId . ' passed validator correctly.');

            // if ($validator->fails()) {
            //     Log::info('Error validating.');

            //     return response()->json(
            //         [
            //             "success" => false,
            //             "message" => 'Error purchasing product. ' . $validator->errors()
            //         ],
            //         400
            //     );
            // };

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

            // Log::info('User id '.$userId.' purchased '.$quantity.' of product_id '.$product_id.' at '.$price.'€ per unit correctly.');

            return response()->json(
                [
                    'success' => true,
                    // 'message' => 'User id '.$userId.' purchased '.$quantity.' of product_id '.$product_id.' at '.$price.'€ per unit correctly.'
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::info('Error purchasing product '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error purchasing product.'
                ],
                400
            );
        }
    }
}