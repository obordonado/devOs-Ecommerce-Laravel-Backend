<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\FlareClient\Time\Time;

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
            /// crea id en sales para que no de error  restraint
            $sale = new Sale();
            $sale->user_id = $userId;     
            $sale->save();


            /// coge y ordena valores de id en sales
            $sale_id = DB::table('sales')
            ->orderByDesc('updated_at')
            ->latest()
            ->get('id')
            ->first();
           

            /// Devuelve el valor del último id generado en tabla sales.
            $valor = $sale_id->id;
            Log::info('usuario genera nuevo id '.$sale_id->id.' en sales ');

            /// Devuelve el valor del id del usuario que hace la compra.
            $user = DB::table('sales')
            ->where('user_id','=', $userId)
            ->get()
            ->first();
            Log::info('usuario que hace la compra '.$user->id);


            if($valor && $user){

                $valor = $valor-$valor+1;
            };
           
            $product_id = $request->input('product_id');
            $quantity = $request->input('quantity');
            $price = $request->input('price');
    
            $purchase = new Purchase();
            $purchase->sale_id = $valor;
            $purchase->product_id = $product_id;
            $purchase->quantity = $quantity;
            $purchase->price = $price;
            $purchase->save();

            $sum = DB::table('purchases')
            ->where('sale_id','=', $valor)
            ->sum('price');
            Log::info('log de $sum '.$sum);

            $total_price = DB::table('sales')
            ->where('id', '=', $valor)->latest()
            ->where('user_id','=',$userId)
            ->insertGetId(array
            (               
                'user_id' => $userId,
                'total_price' => $sum,
                "created_at" => \Carbon\Carbon::now(), # new \Datetime()
                "updated_at" => \Carbon\Carbon::now(),  # new \Datetime()
            )                
            );


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

