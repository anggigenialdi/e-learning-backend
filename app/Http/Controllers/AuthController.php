<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
            // Cek duplikat data
            $duplicate_data = User::where( 'email', $request->input('email') )->first();

            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email Sudah Terdaftar',
                    'data' => null,

                ], 425);
            }
            else if( $request->input('password') !==  $request->input('password_confirmation')){
                return response()->json([
                    'success' => false,
                    'message' => 'Password Tidak Sama',
                    'data' => null,
                ], 425);
            } else {
                $user = new User;
                $user->nama     = $request->input('nama');
                $user->email    = $request->input('email');
                $plainPassword  = $request->input('password');
                $user->password = app('hash')->make($plainPassword);
                $user->save();
            } 

            //return successful response
            return response()->json([
                'success' => true,
                'message' => 'Registrasi Berhasil',
                'user' => $user, 
            ], 201);
        

    }

    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        $user = User::where('email',$request->email)->first();

        $data_user = [
            'id' => $user->id, 
            'nama' => $user->nama, 
            'email' => $user->email, 
            'role' => $user->role, 
        ];

        if($user){
            if (Hash::check($request->password, $user->password)) {
                $token = base64_encode(Str::random(100));

                    $user->update([
                        'token' => $token
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Success Login',
                        'data'=>[
                            'data'=>$data_user,
                            'token'=> $token,
                        ]
                    ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Fail! Password Wrong',
                    'data' => '',
                ],400);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Login Fail! Email Not Found',
                'data' => '',
            ],400);
        }
    }



}