<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_Profile;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return response()->json([
            'user' => Auth::user()
        ], 200);
    }

    public function allUsers()
    {
        if (Auth::user()->role === 'admin'){
            return response()->json([
                'success' => true,
                'message' => 'List All User',
                'users' =>  User::OrderBy('id', 'DESC')->paginate(2),
            ], 200);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'You dont have permission!!'
            ], 404);
        }
        
    }

    public function singleProfile($id)
    {
        try {
            $user_profile = User_Profile::findOrFail($id);
            dd('kopong');

            return response()->json([
                'success' => true,
                'message' => 'User Profile',
                'user' => $user_profile
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'user not found!'
            ], 404);
        }

    }

    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'List User By Id',
                'user' => $user
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'user not found!'
            ], 404);
        }

    }

}