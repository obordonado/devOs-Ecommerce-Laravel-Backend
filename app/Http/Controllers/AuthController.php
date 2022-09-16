<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    const ROLE_USER = 1;

    public function register(Request $request)
    {
        try {
            Log::info('Creating new user');

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'surname' => ['required', 'string', 'max:255'],
                    'phone_number' => ['required', 'string','min:9','max:12'],
                    'email' => ['required', 'string', 'email', 'max:35' . 'unique:users'],
                    'password' => ['required','string','min:6','max:18'],
                    'payment_type' =>['required','string'],
                    'address'=> ['required', 'string', 'max:255']
                ]
            );

            if ($validator->fails()) {
                Log::info('Error in data introduced at Creating new user.');

                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create(
                [
                    'name' => $request->input('name'),
                    'surname' => $request->input('surname'),
                    'phone_number' => $request->input('phone_number'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
                    'payment_type' => $request->input('payment_type'),
                    'address' => $request->input('address'),
                ]
            );

            $user->roles()->attach(self::ROLE_USER);

            $token = JWTAuth::fromUser($user);
            Log::info('New user created');

            return response()->json(compact('user', 'token'), 201);
        } catch (\Exception $exception) {
            Log::info('Error creating user. ' . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error creating user.'

                ]
            );
        }
    }

    public function login(Request $request)
    {
        try {
            Log::info('Trying -> Login user');

            $input = $request->only('email', 'password');
            $jwt_token = null;

            if (!$jwt_token = JWTAuth::attempt($input)) {
                Log::info('Error Auth -> User Login');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Invalid Email or Password',
                    ],
                    401
                ); 
            }

            Log::info('User Login -> Correct');

            return response()->json(
                [
                    'success' => true,
                    'token' => $jwt_token,
                ]
            );
        } catch (\Exception $exception) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error loging in.'
                ],
                401
            );
        }
    }

    public function me(Exception $exception)
    {
        try {
            Log::info('User retrieved own profile correctly.');

            return response()->json(
                [

                    'success' => true,
                    'message' => 'Retrieved Profile successfully',
                    'data' => auth()->user()
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::info("Error accessing profile" . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error retrieving own profile.'
                ],
                401
            );
        }
 
    }

    public function editOwnProfile(Request $request, $id)
    {
        try {

            $userId = auth()->user()->id;

            Log::info('User id ' . $userId . ' updating own profile');

            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'surname' => ['required', 'string', 'max:255'],
                    'phone_number' => ['required', 'string','min:9','max:12'],
                    'password' => ['required','string','min:6','max:18'],
                    'payment_type' =>['required','string'],
                    'address'=> ['required', 'string', 'max:255']
                ]
            );

            if ($validator->fails()) {
                Log::info('User id ' . $userId . ' validation error updating own profile.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }
            $user = User::query()
                ->where('id', '=', $userId)
                ->find($id);

            if (!$user) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'User does not exist.',
                    ],
                    404
                );
            }

            $name = $request->input('name');
            $surname = $request->input('surname');
            $phone_number = $request->input('phone_number');
            $password = $request->input('password');
            $paymet_type=$request->input('payment_type');
            $address = $request->input('address');

            if (isset($name)) {
                $user->name = $name;
            }
            if (isset($surname)) {
                $user->surname = $surname;
            }
            if (isset($phone_number)) {
                $user->phone_number = $phone_number;
            }
            if (isset($password)) {
                $user->password = Hash::make($password);
            }
            if (isset($paymet_type)) {
                $user->payment_type = $paymet_type;
            }
            if (isset($address)) {
                $user->address = $address;
            }

            $user->save();

            Log::info('User id ' . $userId . ' updated own profile correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User id ' . $id . ' updated own profile correctly.',
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::info('An error ocurred while updating user id ' . $userId . ' profile ' . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating profile.'
                ],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        Log::info('User Logging out');

        $this->validate($request, [
            'token' => 'required'
        ]);

        try {

            JWTAuth::invalidate($request->token);

            Log::info('User Logged out');

            return response()->json(
                [
                'success' => true,
                'message' => 'User logged out successfully'
                ]
            );
        } catch (\Exception $exception) {
            Log::info('Error Logging out' . $exception->getMessage());

            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
