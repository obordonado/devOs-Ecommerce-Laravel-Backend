<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function createNewMessage(Request $request)
    {
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId. ' is creating a message..');

            $validator = Validator::make($request->all(), [
               'channel_id' => ['required','integer'],
                'message' => ['required','string','min:2','max:100']
            ]);

            if ($validator->fails()) {
                
                Log::info('Validator failed. '.$validator->errors());

                return response()->json(
                    [
                        "success" => false,
                        "message" => 'Error creating message. '.$validator->errors()
                    ],
                    400
                );
            };

            $channelId = $request->input('channel_id');

            $channel = Channel::find($channelId);

            Log::info('Verifying if channel exists...');

            if (!$channel) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Channel does not exist.'
                    ],
                    404
                );
            }
            Log::info('Channel '.$channelId.' does exist.');

            Log::info("Finding out if user has joined the channel...");

            $userInChannel = DB::table('channel_user')
                ->where('user_id', $userId)
                ->where('channel_id', $channelId)
                ->first();

            if(!$userInChannel){
                Log::info('User id '.$userId.' has not joined the channel yet.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User id '.$userId.' must join channel first.'
                    ],
                    401
                );
            }

            $messageText = $request->input('message');

            $message = new Message();
            $message->user_id = $userId;
            $message->channel_id = $channelId;
            $message->message = $messageText;

            $message->save();

            Log::info('User id '.$userId.' has sent a message correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id '.$userId.' sent message correctly.'
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error sending message: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error sending message.'
                ],
                500
            );
        }
    }

    public function getOwnMessages(){

        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' getting own messages.');

            $messages = Message::query()
            -> where('user_id', '=' , $userId)
            -> get()
            -> toArray();

            Log::info('User id '.$userId.' got own messages without error.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Messages retrieved successfully.',
                    'data' => $messages
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::error('Error getting messages. '. $exception->getMessage());

            return response()->json(
                [
                'success'=> false,
                'message' => 'Messages could not be retrieved.'
                ],
                400
            );
        }
    }
    
    public function getMessageByMsgId($id) {

        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' getting message by message id...');

            $message = Message::query()
            
            ->where('id', '=' , $id)
            ->where('user_id' , '=' , $userId)
            ->get()->toArray();

            if(!$message) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Message does not exist.'
                    ],
                    404
                );
            }
            
            Log::info('User id '.$userId.' retrieved message by id without error.');

            return response()->json(
                [
                'success'=> true,
                'message' => 'Message retrieved successfully.',
                'data'=> $message
                ],
                200
            );
    
        } catch (\Exception $exception) {
            Log::info('Error getting message by Id '.$exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting message by Id.'
                ],
                400
            );
        }
    }

    public function updateMessageByMsgId(Request $request, $id){
        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' updating message...');

            $validator = Validator::make($request->all(), [
                'channel_id' => ['required','integer'],
                'message' => ['required','string','min:2','max:100']
            ]);

            if($validator->fails()) {

                Log::info('User id '.$userId.' validation error updating message.'.$validator->errors());

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User id '.$userId.' validation error updating message. '.$validator->errors()
                    ],
                    400
                );
            }            
            Log::info('User id '.$userId.' passed validator correctly.');

            $message = Message::query()
            -> where('user_id', '=' , $userId)
            -> find($id);            

            if (!$message){
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This message does not exist.',
                    ],
                    404
                );
            }

            $channelId = $request->input('channel_id');
            $userMessage = $request->input('message');

            if(isset($channel_id)){
                $message->channel_id = $channelId;
            }

            if(isset($message)){
                $message->message = $userMessage;
            }

            $message->save();

            Log::info('User id '.$userId.' updated message correctly.');

            return response()->json(

                [
                    'success' => true,
                    'message' => 'Message ' . $id . ' updated correctly.'

                ],
                200
                );

        } catch (\Exception $exception) {
            
            Log::info('Error updating message. ' . $exception->getMessage());
            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error updating message.'
                ],
                400
            );
        }
    }

    public function delMessageById($id){
        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' deleting message...');

            $message = Message::query()-> find($id);            

            if (!$message){
                Log::info('Message does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This message does not exist.',
                    ],
                    404
                );
            }

            $message->delete();

            Log::info('User id '.$userId.' deleted message correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Message '.$id.' deleted correctly.',

                ],
                200
                );

        } catch (\Exception $exception) {
            
            Log::info('Error deleting message. '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error deleting message.'
                ],
                400
            );
        }
    }

    public function getMessagesByChannelId($channelId){

        try {
            Log::info('Getting messages by channel id...');

            $messages = Message::query()
            ->where('channel_id', '=', $channelId)
            ->select('users.name as user', 'messages.message')
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->get()
            ->toArray();

            if(!$messages) {
                Log::info('There are no messages in channel '.$channelId);

               return response() ->json(
                [
                    'success'=> false,
                    'message'=> 'Could not find any message on this channel.'
                ],
                404
            );
            }

            Log::info('Got messages by channel id correctly.');
            
            return response()->json(
                [
                    'success' => true,
                    'message'=> 'Got messages by channel id correctly.',
                    'data' => $messages
                ],
                200
            );
            
        } catch (\Exception $exception) {
            
            Log::info('Error getting messages by channel id. '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Error getting messages by channel id.'
                ],
                400
            );
        }
    }


}
