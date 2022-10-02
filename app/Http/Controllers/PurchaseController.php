<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function createPurchase(Request $request)
    {
        try {            

            $userId = auth()->user()->id;
            Log::info('User '.$userId.' trying to create purchase...');
    
            $validator = Validator::make($request->all(),
            [
                // 'sale_id' => ['required', 'integer'],
                'product_id' => ['required', 'integer'],
                'quantity' => ['required', 'string'],
                'price' => ['required','string']
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


            $sale = new Sale();
            $sale->user_id = $userId;     
            $sale->save();


            $sale_id = DB::table('sales')
            ->orderByDesc('updated_at')
            ->latest()
            ->get('id')
            ->first();
            
            Log::info($sale_id->id);

            $valor = $sale_id->id;

            if($valor){

                $valor = $valor-$valor+1;
            };

            
            $product_id = $request->input('product_id');
            $quantity = $request->input('quantity');
            $price = $request->input('price');
    
            $purchase =  new Purchase();
            $purchase->sale_id = $valor;
            $purchase->product_id = $product_id;
            $purchase->quantity = $quantity;
            $purchase->price = $price;
            $purchase->save();





            Log::info('User id '.$userId.' created a purchase correctly.');
            return response()->json(
                [
                    "success" => true,
                    "message" => 'User id '.$userId.' created purchase correctly.'
                ],
                200
            );


        } catch (\Exception $exception) {
            Log::info('Error creating new purchase '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating purchase by user id '.$userId
                ],
                400
            );
        }
    }
}

