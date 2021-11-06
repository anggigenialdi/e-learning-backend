<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postProfile(Request $request)
    {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'no_kontak' => 'required',
            'alamat' => 'required|string',
            'no_rekening' => 'required',
            'bank' => 'required|string',
        ]);

        $data = Profile::firstOrNew([
            'user_id' => $request->user_id,
            'no_kontak' => $request->no_kontak,
            'alamat' => $request->alamat,
            'no_rekening' => $request->no_rekening,
            'bank' => $request->bank,
        ]);

        // Hak akses input data user login atau admin
        if ( (Auth::user()->role === 'admin') || (Auth::id() == $data->user_id) ){
            try {

                //Cek duplicate input data
                $existing_data = $data->where('user_id', $data->user_id)->first();
                if ( $existing_data ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'user already used',
                        'data' => $existing_data
                    ], 425);
                } else {

                    $data->save();
                }
                if(!$data == null){
                    //return successful response
                    return response()->json([
                        'success' => true,
                        'message' => 'Successfully complete profile',
                        'user' => $data, 
                    ], 201);
                }else {
                    return response()->json([
                        'success' => false,
                        'message' => 'user already used',
                    ], 425);
                }
            } catch (\Exception $e) {
                //return error message
                return response()->json([
                    'success' => false,
                    'message' => 'Complete profile Failed or dont have acount'
                ], 409);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
    }

    public function profile()
    {
        return response()->json([
            'success' => true,
            'message' => 'This data user login',
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
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
        
    }

    public function singleProfile($id)
    {
        try {
            //cek user login jika bukan maka data tidak akan ditampilkan
            if (Auth::id() != $id ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access to that resource is forbidden!'
                ], 403);
            } else {
                //get user profile 
                $user_auth = Profile::findOrFail($id)
                ->user::with([
                        'user_profile'  => fn ($query) => $query
                        ->select(
                            'user_id',
                            'no_kontak',
                            'alamat',
                            'no_rekening',
                            'bank')
                    ])->findOrFail($id);
            }

            return response()->json([
                'success' => true,
                'message' => 'User Profile',
                'user' => ([
                    $user_auth
                ])
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'user not found!'
            ], 404);
        }

    }


}