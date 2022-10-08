<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserController extends Controller
{
    const ROLE_SUPER_ADMIN = 21;

    public function addSuperAdminRoleToUser($id)
    {
        try {            
            $userId = auth()->user()->id;

            Log::info('Adding role super admin to user '.$id);
            $user = User::find($id);
            $user->roles()->attach(self::ROLE_SUPER_ADMIN);
            Log::info(('Added super admin role to user '.$id.' correctly.'));

            return response()->json(
                [
                    'success' => true,
                    'message'=> 'Added super admin role to user '.$id
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::info('Error adding role super admin to user '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message'=> 'Page not found'
                ],
                404
            );
        }
    }

    public function removeSuperAdminRoleFromUser($id)
    {
        try {            
            $userId = auth()->user()->id;

            Log::info('Removing role super admin from user '.$id.'.');
            $user = User::find($id); 
            $user->roles()->detach(self::ROLE_SUPER_ADMIN);
            Log::info(('Removed super admin role from user '.$id.' correctly.'));

            return response()->json(
                [
                    'success' => true,
                    'message'=> 'Removed super admin role from user '.$id
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::info('Error deleting role super admin from user '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message'=> 'Page not found.'
                ],
                404
            );
        }
    }

    public function getRoleUserByAdmin($id)
    {
        try {
            
            $userId = auth()->user()->id;

            Log::info('getting all role id 1 users by super admin...');
    
            $user = Role::find($id);

            $users = DB::table('role_user')
            ->select()
            ->where('role_id','=','1')                                
            ->get()
            ->toArray();  

            Log::info(('Retrieved all role id 1 users correctly.'));

            return response()->json(
                [
                    'success' => true,
                    'message'=> 'Retrieved all role id 1 users correctly.',
                    'data' => $users
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::info('Error getting all role id 1 users by super admin. '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message'=> 'Page not found'
                ],
                404
            );
        }
    }

    public function delUserById($id)
    {        
        try {
            $userId = auth()->user()->id;

            Log::info('User id '.$userId.' deleting user...');
            $user = User::query()-> find($id);            

            if (!$user){
                Log::info('User id '.$id.' does not exist.');

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Page not found.',
                    ],
                    404
                );
            }

            $user->delete();
            Log::info('User id '.$userId.' deleted user id '.$id.' correctly.');

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User '.$id.' deleted correctly.',
                ],
                200
                );
        } catch (\Exception $exception) {            
            Log::info('Error deleting user. '.$exception->getMessage());

            return response()->json(
                [
                    'success'=> false,
                    'message' => 'Page not found.'
                ],
                404
            );
        }
    }

    public function getSalesByStatus(Request $request)
    {
        try {            
            Log::info('Getting sales by status...');

            $status = $request->input('status');
            $status = Sale::query()->where('status','=',$status)->get()->toArray();

            if(!$status) {
                Log::info('Status does not exist.');
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Chosen status does not exist.'
                    ],
                    400
                );
            }
            Log::info('Sales were retrieved by status correctly.');
            return response()->json(
                [    
                    'success' => true,
                    'message' => 'Products were retrieved by status correctly.',
                    'data' => $status
                ],
                200
        );
        } catch (\Exception $exception) {
            Log::info('Error getting products by status. '. $exception->getMessage());

            return response()->json(
                [                
                    'success' => false,
                    'message' => 'Failed to get sales by status.'
                ],
                400
            );
        }
    }
    
}
