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

      Log::info('User ' . $userId . ' trying to create purchase...');

      $validator = Validator::make(
        $request->all(),
        [
          // 'sale_id' => ['required', 'integer'],
          'product_id' => ['required', 'integer'],
          'quantity' => ['required', 'string'],
          'price' => ['required', 'string']
        ]
      );
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

      /// crea id en sales si no existe para evitar error restraint.//////////////////////////////////////

      if (DB::table('sales')->where('user_id', $userId)->doesntExist()) {
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
        Log::info('usuario '.$userId.' (doesntExist) genera nuevo id en sales ' . $sale_id->id . ' en sales ');







        if ($valor == 1) { //cambiado a /2 y de posicion sobre justo inferior
            
          $valor = $valor;      
        
        }else if($valor / 2 == 0){

          $valor = $valor+1;
        
        };




        /// Devuelve el valor del id del usuario que hace la compra.
        $user = DB::table('sales')
          ->where('user_id', '=', $userId)
          ->get()
          ->first();
        Log::info('usuario que hace la compra ' . $user->id);



        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $price = $request->input('price');

        Log::info('valor de id antes de hacer new purchase '.$userId);
        $purchase = new Purchase();
        $purchase->user_id = $userId;    
        $purchase->sale_id = $valor;
        $purchase->product_id = $product_id;
        $purchase->quantity = $quantity;
        $purchase->price = $price;
        $purchase->save();

        $sum = DB::table('purchases')
          ->where('sale_id', '=', $valor)
          ->where('user_id','=',$userId)  
          ->sum('price');
        Log::info('log de $sum ' . $sum);

        // insert total price in last sales id with last userId.
        $total_price = DB::table('sales')
        ->where('user_id', '=', $userId)
        ->where('id', '=', $sale_id->id)->latest()
        ->update(['total_price' => $sum]);

      } else {

        if (DB::table('sales')->where('user_id', $userId)->exists()) {

          /// coge y ordena valores de id en sales
          $sale_id = DB::table('sales')
            ->orderByDesc('updated_at')
            ->latest()
            ->get('id')
            ->first();

          /// Devuelve el valor del último id generado en tabla sales.
          $valor = $sale_id->id;
          Log::info('usuario '.$userId.' (exists) genera nuevo id en sales ' . $sale_id->id . ' en sales ');


          if ($valor == 1) { //cambiado a /2 y de posicion sobre justo inferior
            
            $valor = $valor;      
          
          }else if($valor / 2 == 0){
  
            $valor = $valor+1;
          
          };


          /// Devuelve el valor del id del usuario que hace la compra.
          $user = DB::table('sales')
            ->where('user_id', '=', $userId)
            ->get()
            ->first();
          Log::info('usuario que hace la compra ' . $user->id);


          $product_id = $request->input('product_id');
          $quantity = $request->input('quantity');
          $price = $request->input('price');

          $purchase = new Purchase();
          $purchase->user_id = $userId;
          $purchase->sale_id = $valor;
          $purchase->product_id = $product_id;
          $purchase->quantity = $quantity;
          $purchase->price = $price;
          $purchase->save();

          $sum = DB::table('purchases')
            ->where('sale_id', '=', $valor)
            ->where('user_id','=',$userId)
            ->sum('price');
          Log::info('log de $sum ' . $sum);

          // insert total price in last sales id with last userId.
          $total_price = DB::table('sales')
            ->where('id', '=', $sale_id->id)->latest()
            ->where('user_id', '=', $userId)
            ->update(['total_price' => $sum]);
        }
      }



















      Log::info('User id ' . $userId . ' created a purchase correctly.');
      return response()->json(
        [
          "success" => true,
          "message" => 'User id ' . $userId . ' created purchase correctly.'
        ],
        200
      );
    } catch (\Exception $exception) {
      Log::info('Error creating new purchase ' . $exception->getMessage());

      return response()->json(
        [
          'success' => false,
          'message' => 'Error creating purchase by user id ' . $userId
        ],
        400
      );
    }
  }
}
