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

      Log::info('Iniciando primera parte del código.');

      // Getting last sale id with status "acquitted, delivering, delivered".
      $sale_id = DB::table('sales')
      ->where('user_id', '=', $userId)
      ->where('status', '=', 'acquitted')
      ->orWhere('status','=','delivering')
      ->orWhere('status','=','delivered')
      ->orderByDesc('updated_at')
      ->latest()
      ->get('id')
      ->first();

      // Log::info('User id '.$userId.' from "exists" gets id ->'. $sale_id->id.'<- of "status acquitted, delivering or delivered" in table sales if it exists.');
      Log::info('En primera parte de método');


      if ($sale_id)
      {
        Log::info('inicio método control para buscar si está in progress o no, para hacer save() o no..');

        $find_status = DB::table('sales')
        ->where('user_id','=',$userId)
        ->where('status','=', 'in progress')
        ->get('id')
        ->first();

        // Log::info('User id '.$userId.' has status "in progress" in sales->id -> '.$find_status.' <-');

        if(!$find_status)
        {
        Log::info('User id '.$userId.' "has no in progress id".');
        //User has purchase in acquitted, delivering or delivered -> Create new id value in table sale.
        $sale = new Sale();
        $sale->user_id = $userId;
        $sale->status = 'in progress';
        $sale->save();


        Log::info('Getting  "new id of in progress"...'); ////// ULTIMO 19:40  METO ESTE BLOQUE ENTERO 
        // Getting last sale id with status in progress.
        $sale_id = DB::table('sales')
        ->where('user_id', '=', $userId)
        ->where('status', '=', 'in progress')
        ->orderByDesc('updated_at')
        ->latest()
        ->get('id')
        ->first();
        }


        
        /////////// CREO QUE ESTA PARTE DEL CODIGO SE PUEDE BORRAR TRAS PROBAR QUE TODO VAYA BIEN /////
        elseif($find_status)
        {
          Log::info('Getting  "new id of in progress"...');
          // Getting last sale id with status in progress.
          $sale_id = DB::table('sales')
          ->where('user_id', '=', $userId)
          ->where('status', '=', 'in progress')
          ->orderByDesc('updated_at')
          ->latest()
          ->get('id')
          ->first();
        };
        /////////// CREO QUE ESTA PARTE DEL CODIGO SE PUEDE BORRAR TRAS PROBAR QUE TODO VAYA BIEN /////




        
        // Returns last id value in sales table.
        $valor = $sale_id->id;
        Log::info('User id '.$userId.' from "exists" introduces data in table sales -> id ' . $valor);
        Log::info('En primera parte de método');


        // Controlling that sale id is correct in purchases.
        if ($valor == 1) {

          $valor = $valor;
        } else if ($valor / 2 == 0) {

          $valor = $valor + 1;
        };

        // Returns value of the userid that is purchasing AND is still in progress.
        $user = DB::table('sales')
        ->where('user_id', '=', $userId)
        ->where('status', '=', 'in progress')
        ->get()
        ->first();
      
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
        Log::info('$sum value = ' . $sum);
        Log::info('En primera parte de método');


        // Inserting total price in last sales id with last userId.
        $total_price = DB::table('sales')
        ->where('id', '=', $sale_id->id)->latest()
        ->where('user_id', '=', $userId)
        ->update(['total_price' => $sum]);
        Log::info('Sale de primera parte de método');

      }elseif(DB::table('sales')->where('user_id', $userId)->exists()){
        Log::info('Entrando en segunda parte de método');

                  // Getting last sale id.
                  $sale_id = DB::table('sales')// <--------AQUI HAY QUE MIRAR
                  ->where('user_id', '=', $userId)
                  ->where('status', '=', 'in progress')
                  ->orderByDesc('updated_at')
                  ->latest()
                  ->get('id')
                  ->first();
      
                // Returns last id value in sales table.
                $valor = $sale_id->id;
                Log::info('User id '.$userId.' from "exists" introduces data in table sales -> id ' . $sale_id->id);
                Log::info('En segunda parte de método');
      
      
                // Controlling that sale id is correct in purchases.
                if ($valor == 1) {
      
                  $valor = $valor;
                } else if ($valor / 2 == 0) {
      
                  $valor = $valor + 1;
                };
      
                // Returns value of the userid that is purchasing AND is still in progress.
                $user = DB::table('sales')
                  ->where('user_id', '=', $userId)
                  ->where('status', '=', 'in progress')
                  ->get()
                  ->first();
                
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
                Log::info('$sum value = ' . $sum);
                Log::info('En segunda parte de método');
      
      
                // Inserting total price in last sales id with last userId.
                $total_price = DB::table('sales')
                  ->where('id', '=', $sale_id->id)->latest()
                  ->where('user_id', '=', $userId)
                  ->update(['total_price' => $sum]);
      }






      ////////////////////////////////////////////////////////////////////////////


      

      // Creating first id in sales in order to avoid restraint error.
      elseif (DB::table('sales')->where('user_id', $userId)->doesntExist()) {
        Log::info('Iniciando tercera parte del código.');

        $sale = new Sale();
        $sale->user_id = $userId;
        $sale->status = 'in progress';
        $sale->save();

        // Getting last sale id.
        $sale_id = DB::table('sales')
          ->orderByDesc('updated_at')
          ->latest()
          ->get('id')
          ->first();

        // Returns last id value in sales table.
        $valor = $sale_id->id;
        Log::info('usuario ' . $userId . ' "doesntExist" generates new id '.$sale_id->id.' in sales table.');
        Log::info('En tercera parte de método');


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
        Log::info('UserId ' . $user->id.' is purchasing product.');
        Log::info('En tercera parte de método');


        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $price = $request->input('price');

        Log::info('id value '.$userId.' of user trying to purchase.');
        Log::info('En tercera parte de método');

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
        Log::info('$sum value = ' . $sum);

        // insert total price in last sales id with last userId.
        $total_price = DB::table('sales')
          ->where('user_id', '=', $userId)
          ->where('id', '=', $sale_id->id)->latest()
          ->update(['total_price' => $sum]);
      } 
        elseif (DB::table('sales')->where('user_id', $userId)->exists()) {
          Log::info('Iniciando cuarta parte del código.');

          // Getting last sale id.
          $sale_id = DB::table('sales')// <--------AQUI HAY QUE MIRAR
            ->where('user_id', '=', $userId)
            ->where('status', '=', 'in progress')
            ->orderByDesc('updated_at')
            ->latest()
            ->get('id')
            ->first();

          // Returns last id value in sales table.
          $valor = $sale_id->id;
          Log::info('User id '.$userId.' from "exists" introduces data in table sales -> id ' . $sale_id->id);
          Log::info('En cuarta parte de método');


          // Controlling that sale id is correct in purchases.
          if ($valor == 1) {

            $valor = $valor;
          } else if ($valor / 2 == 0) {

            $valor = $valor + 1;
          };

          // Returns value of the userid that is purchasing AND is still in progress.
          $user = DB::table('sales')
            ->where('user_id', '=', $userId)
            ->where('status', '=', 'in progress')
            ->get()
            ->first();
          
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
          Log::info('$sum value = ' . $sum);
          Log::info('En cuarta parte de método');


          // Inserting total price in last sales id with last userId.
          $total_price = DB::table('sales')
            ->where('id', '=', $sale_id->id)->latest()
            ->where('user_id', '=', $userId)
            ->update(['total_price' => $sum]);
            Log::info('Saliendo de cuarta parte de método');

        }

//////////////////////////////////////////////////////////////////////////////////



















////////////////////////////////////////////////////////////////////////////////////
      
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