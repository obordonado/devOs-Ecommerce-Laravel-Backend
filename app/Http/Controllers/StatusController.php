<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    public function editStatusById(Request $request, $id)
    {        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' updating status...');

            $validator = Validator::make($request->all(),
            [
                'status' => ['required','string']
            ]);

            if($validator->fails()) {
                Log::info('User id '.$userId.' validation error updating status. '.$validator->errors());

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User id '.$userId.' validation error updating status. '.$validator->errors()
                    ],
                    400
                );
            }            
            Log::info('User id '.$userId.' passed validator correctly.');

            $sale = Sale::query()-> find($id);            

            if (!$sale){
                Log::info('Sale '.$id.' does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This sale does not exist.',
                    ],
                    404
                );
            }

            $status = $request->input('status');

            if(isset($status)){
                $sale->status = $status;
            }

            $sale->save();
            Log::info('User id '.$userId.' updated status to '.$status.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Sale '.$id.' updated status to '.$status.' correctly.'
                ],
                200
                );

        } catch (\Exception $exception) {            
            Log::info('Error updating product. ' . $exception->getMessage());
            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error updating status.'
                ],
                400
            );
        }
    }
}
