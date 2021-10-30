<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function allUsers()
    {
        return response()->json(['users' =>  User::all()], 200);
    }

    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }

    }

}