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

      // Creating first id in sales in order to avoid restraint error.
      if (DB::table('sales')->where('user_id', $userId)->doesntExist()) {
        $sale = new Sale();
        $sale->user_id = $userId;
        $sale->save();

          // Getting last sale id.
        $sale_id = DB::table('sales')
        ->orderByDesc('updated_at')
        ->latest()
        ->get('id')
        ->first();

        // Returns last id value in sales table.
        $valor = $sale_id->id;
        Log::info('usuario ' . $userId . ' ("doesntExist" genera nuevo id en sales ' . $sale_id->id . ' en sales ');

        // Controlling that sale id is correct in purchases.
        if ($valor == 1) { 

          $valor = $valor;
        } else if ($valor / 2 == 0) {

          $valor = $valor + 1;
        };

        // Returns value of the userid that is purchasing.
        $user = DB::table('sales')
        ->where('user_id', '=', $userId)
        ->get()
        ->first();
        Log::info('usuario que hace la compra ' . $user->id);

        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $price = $request->input('price');

        Log::info('valor de id antes de hacer new purchase ' . $userId);
        $purchase = new Purchase();
        $purchase->user_id = $userId;
        $purchase->sale_id = $valor;
        $purchase->product_id = $product_id;
        $purchase->quantity = $quantity;
        $purchase->price = $price;
        $purchase->save();

        $sum = DB::table('purchases')
        ->where('sale_id', '=', $valor)
        ->where('user_id', '=', $userId)
        ->sum('price');
        Log::info('log de $sum '.$sum);

        // insert total price in last sales id with last userId.
        $total_price = DB::table('sales')
        ->where('user_id', '=', $userId)
        ->where('id', '=', $sale_id->id)->latest()
        ->update(['total_price' => $sum]);
      } else {

        if (DB::table('sales')->where('user_id', $userId)->exists()) {

          // Getting last sale id.
          $sale_id = DB::table('sales')
          ->where('user_id','=', $userId)
          ->where('status','=', 'in progress')
          ->orderByDesc('updated_at')
          ->latest()
          ->get('id')
          ->first();

          // Returns last id value in sales table.
          $valor = $sale_id->id;
          Log::info('Usuario id '.$userId.' ( de "exists") genera nuevo id '.$sale_id->id .' en tabla sales ');

          // Controlling that sale id is correct in purchases.
          if ($valor == 1) { 

            $valor = $valor;
          } else if ($valor / 2 == 0) {

            $valor = $valor + 1;
          };

          // Returns value of the userid that is purchasing AND is still in progress.
          $user = DB::table('sales')
          ->where('user_id', '=', $userId)
          ->where('status','=','in progress')/// <- tocado aquí añadido linea completa.
          ->get()
          ->first();
          Log::info('usuario que hace la compra ' . $user->id);

          $saleId = DB::table('sales')
          ->where('user_id', '=', $userId)
          ->get('id')
          ->first();
          

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
          ->where('user_id', '=', $userId)
          ->sum('price');
          Log::info('log de $sum ' . $sum);

          // Inserting total price in last sales id with last userId.
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
