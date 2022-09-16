<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{

        public function createChannel(Request $request) {

        try {

            $userId = auth()->user()->id;

            Log::info('User id '.$userId. ' is creating a channel..');

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'game_id' => ['required','integer']
            ]);
            Log::info('User id '.$userId.' passed validator correctly.');

            if ($validator->fails()) {

                return response()->json(
                    [
                        "success" => false,
                        "message" => 'Error creating new channel '.$validator->errors()
                    ],
                    400
                );
            };

            $name = $request->input('name');
            $gameId = $request->input('game_id');

            $channel = new Channel();
            $channel->name = $name;
            $channel->game_id = $gameId;
            $channel->save();
            $channel->users()->attach($userId);

            Log::info('User id '.$userId.' created channel '.$name. ' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' created channel '.$name. ' correctly.'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::info('Error creating new channel '. $exception->getMessage());
            
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating channel.',
                    'cause' => 'Channel already exists.'
                ],
                400
            );
        }
    }

    public function getAllChannels(){
        try {

            Log::info('Getting all channels...');

           $channels = Channel::query()->select('name')->get()->toArray();

           Log::info('All channels retrieved correctly');

            return response()->json(

                    [
                    'success'=> true,
                    'message' => 'Available channels retrieved successfully.',
                    'data'=> $channels
                    ],
                    200
                );
        
        } catch (\Exception $exception) {

            Log::error('Error getting channels. '.$exception->getMessage());

            return response()->json(

                [
                'success'=> false,
                'message' => 'Available channels could not be retrieved.'
                ],
                400
            );
        }
    }

    public function getChannelById($id){
        try {
            
            Log::info('Getting channel name by channel id '.$id.'...');
            $channel = Channel::findOrFail($id);
            Log::info('Getting channel name by channel id '.$id.' worked correctly.');

            return response()->json(
                [
                'success'=> true,
                'message' => 'Channel name retrieved successfully.',
                'data'=> $channel
                ],
                200
            );
    
        } catch (\Exception $exception) {

            Log::error('Getting channel name by channel id failed. '.$exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Could not retrieve channel name by channel id '.$id
                ],
                400
            );
        }
    }

    public function getChannelByName(Request $request){

        try {
            
            Log::info('Getting game by title...');

            $name = $request->input('name');

            $channels = Channel::query()->where('name','=',$name)->get()->toArray();
    
            return response()->json([
    
                'success' => true,
                'message' => 'Channel was retrieved by title correctly.',
                'data' => $channels
            ]);
            
            Log::info('Channel was retrieved by title correctly.');

        } catch (\Exception $exception) {

            Log::info('Error getting channel by title. '. $exception->getMessage());

            return response()->json([
                
                'success' => false,
                'message' => 'Failed to get channel by title.'
            ]);
        }
    }

    public function joinChannelById($id)
    {
        try {

            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' trying to join channel '.$id.'...');


            $channel = Channel::query()->find($id);

            $channel->users()->attach($userId);

            Log::info('User id '.$userId.' joined channel id '.$id.' correctly.');

            if (!$channel) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Channel '.$id.' does not exist."
                    ],
                    404
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' joined channel id '.$id.' correctly.'
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error('Error joining channel id '.$id.'. ' . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error joining channel id '.$id
                ],
                400
            );
        }
    }

    public function exitChannelById($id)
    {
        try {

            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' trying to exit channel '.$id.'...');


            $channel = Channel::query()->find($id);



            $channel->users()->detach($userId);

            Log::info('User id '.$userId.' exited channel id '.$id.' correctly.');

            if (!$channel) {

                Log::info('User does not exist in channel id '.$id);

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Channel ' .$id. ' does not exist.'
                    ],
                    404
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' exited channel id '.$id.' correctly.'
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error('Error exiting channel id '.$id.'. ' . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error exiting channel id '.$id
                ],
                400
            );
        }
    }

    public function sadminDelChannelById($id)
    {
        try {
            $userId = auth()->user()->id;

            Log::info('Deleting channel '.$id.' by user id '.$userId);

            $channel = Channel::query()->find($id);

            if (!$channel) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Channel could not be deleted.',
                        'error'  => 'Channel '.$id.' does not exist.'
                    ],
                    404
                );
            }

            $channel->delete($id);

            $channel->users()->detach();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Channel '.$id. ' deleted correctly by super admin '.$userId
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error('Error deleting channel '.$id.' by user id '.$userId.'. '  . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error deleting the channel"
                ],
                400
            );
        }
    }


}
