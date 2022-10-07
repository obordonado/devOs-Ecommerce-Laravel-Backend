<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
  public function rateSaleById(Request $request, $id)
  {
    try {
      $userId = auth()->user()->id;

      Log::info('User id ' . $userId . ' updating rating...');

      $validator = Validator::make($request->all(),      
        [
          'rating' => ['required', 'integer', 'min:1', 'max:10']
        ]
      );

      if ($validator->fails()) {
        Log::info('User id ' . $userId . ' validation error updating rating. ' . $validator->errors());

        return response()->json(
          [
            'success' => false,
            'message' => 'User id ' . $userId . ' validation error updating rating. ' . $validator->errors()
          ],
          400
        );
      }
      Log::info('User id ' . $userId . ' passed validator correctly.');

      $sale = Sale::query()->find($id);

      if (!$sale) {
        Log::info('Sale ' . $id . ' does not exist.');

        return response()->json(
          [
            'success' => false,
            'message' => 'This sale does not exist.',
          ],
          404
        );
      }

      $rating = $request->input('rating');

      if (isset($rating)) {
        $sale->rating = $rating;
      }

      $sale->save();
      Log::info('User id ' . $userId . ' updated rating to ' . $rating . ' correctly.');

      return response()->json(
        [
          'success' => true,
          'message' => 'Sale ' . $id . ' updated rating to ' . $rating . ' correctly.'
        ],
        200
      );
    } catch (\Exception $exception) {
      Log::info('Error updating product. ' . $exception->getMessage());
      return response()->json(
        [
          'success' => false,
          'message' => 'Error updating rating.'
        ],
        400
      );
    }
  }
}
