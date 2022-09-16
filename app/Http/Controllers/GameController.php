<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{

    public function createNewGame(Request $request)
    {
        try {

            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' creating new game...');

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string','min:2','max:35'],
            ]);

            if ($validator->fails()) {

                Log::info('User id '.$userId.' validation error in "title" creating new game. '.$validator->errors());

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Error creating new game.',
                        'error' => $validator->errors()
                    ],
                    400
                );
            };

            $title = $request->input('title');

            $game = new Game();
            $game->title = $title;
            $game->user_id = $userId;
            $game->save();

            Log::info('User id '.$userId.' created game "'.$title.'" correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' created game '.$title.' correctly.'
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error('Error creating new game '.$title.' => '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating '.$title.'.',
                    'error' => $validator->errors()
                ],
                500
            );
        }
    }

    public function getAllGames(){
        try {

            Log::info('Getting all games');
            $games = DB::table('games')
            ->select('title')
            ->get()
            ->toArray();  
            //$games = Game::query()->select('title')->get()->toArray();

           Log::info('All games retrieved correctly');

            return response()->json(

                    [
                    'success'=> true,
                    'message' => 'Available games retrieved successfully.',
                    'data'=> $games
                    ],
                    200
                );
        
        } catch (\Exception $exception) {

            Log::error('Error getting available games. '.$exception->getMessage());

            return response()->json(

                [
                'success'=> false,
                'message' => 'Available games could not be retrieved.'
                ],
                400
            );
        }
    }

    public function getGameById($id){
        try {
            
            Log::info('Getting game title by game Id');
            $game = Game::findOrFail($id);
            Log::info('Getting game title by game id worked correctly.');

            return response()->json(
                [
                'success'=> true,
                'message' => 'Game title retrieved successfully.',
                'data'=> $game
                ],
                200
            );
    
        } catch (\Exception $exception) {

            Log::error('Getting game title by game id failed.'.$exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Could not retrieve game title by game id.'
                ],
                400
            );
        }
    }

    public function getGameByTitle(Request $request){

        try {
            
            Log::info('Getting game by title...');

            $title = $request->input('title');

            $games = Game::query()->where('title','=',$title)->get()->toArray();
    
            return response()->json([
    
                'success' => true,
                'message' => 'Game was retrieved by title correctly.',
                'data' => $games
            ]);
            
            Log::info('Game was retrieved by title correctly.');

        } catch (\Exception $exception) {

            Log::info('Error getting game by title. '. $exception->getMessage());

            return response()->json([
                
                'success' => false,
                'message' => 'Failed to get game by title.'
            ]);
        }
    }

    public function getOwnGamesByUserId($id) {

        try {

            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' getting own games...');

            $games = User::query()->find($userId)->games;
            
            
            Log::info('User id '.$userId.' retrieved own games by id without error.');

            return response()->json(
                [
                'success'=> true,
                'message' => 'Game/s retrieved successfully.',
                'data'=> $games
                ],
                200
            );
    
        } catch (\Exception $exception) {
            Log::info('Error getting games by user id '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting game/s by user id.'
                ],
                400
            );
        }
    }

    public function updateGameById(Request $request, $id){
        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' updating game...');

            $validator = Validator::make($request->all(), [
                'title' => ['required','string','min:2','max:40'],
            ]);

            if($validator->fails()) {
                Log::info('User id '.$userId.' validation error updating game. '.$validator->errors());
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }            
            Log::info('Passed validator correctly.');

            $game = Game::query()
            -> where('user_id', '=' , $userId)
            -> find($id);            

            if (!$game){
                Log::info('Game does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This game does not exist.',
                    ],
                    404
                );
            }

            $title = $request->input('title');

            if(isset($title)){
                $game->title = $title;
            }

            $game->save();

            Log::info('User id '.$userId.' updated game '.$title.' correctly.');

            return response()->json(

                [
                    'success' => true,
                    'message' => 'User id '.$userId.' updated game '.$title.' correctly.',
                ],
                200
                );

        } catch (\Exception $exception) {
            
            Log::info('Error updating task' . $exception->getMessage());
            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error updating game.'
                ],
                400
            );
        }
    }

    public function deleteGameById(Request $request, $id){
        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' deleting game...');

            $validator = Validator::make($request->all(), [
                'title' => ['required','string','min:1','max:35'],
            ]);

            if($validator->fails()) {

                Log::info('User id '.$userId.' validation error deleting game.'.$validator->errors());

                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }

            $game = Game::query()
            -> where('user_id', '=' , $userId)
            -> find($id);            

            $title = $request->input('title');

            if (!$game){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Game title '.$title.', id '.$id.' does not exist.',
                    ],
                    404
                );
            }

            if(isset($title)){
                $game->title = $title;
            }

            $game->delete();

            Log::info('User id '.$userId.' deleted '.$title.', with id '.$id.' correctly.');

            return response()->json(

                [
                    'success' => true,
                    'message' => 'Game title '.$title.', id '.$id.' deleted correctly.',
                    
                ],
                200
                );

        } catch (\Exception $exception) {
            
            Log::info('User id '.$userId.' error deleting game' . $exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error deleting game.'                    
                ],
                400
            );
        }
    }


}
