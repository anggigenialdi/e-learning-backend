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

    public function storeProfile(Request $request)
    {
        $this->validate($request, [
            'no_kontak' => 'required',
            'alamat' => 'required|string',
            'no_rekening' => 'required',
            'bank' => 'required|string',
        ]);

        try {
            $profile = Profile::where('user_id', Auth::user()->id)->first();

            if (!$profile){
                $profile = new Profile;
                $profile->user_id = Auth::user()->id;
            }
            $profile->no_kontak = $request->no_kontak;
            $profile->alamat = $request->alamat;
            $profile->no_rekening = $request->no_rekening;
            $profile->bank = $request->bank;

            //Cek duplicate input data
            $duplicate_data = $profile->where( 'no_kontak', $profile->no_kontak )->first();
            $duplicate_noreq = $profile->where( 'no_rekening', $profile->no_rekening )->first();
            if ( $duplicate_data || $duplicate_noreq ) {
                return response()->json([
                    'success' => false,
                    'message' => 'user already used',
                    'data' => $duplicate_data, 
                    'data' => $duplicate_noreq,
                    'data' => $profile,

                ], 425);
            } else {

                $profile->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully complete profile',
                'user' => $profile, 
                ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Complete profile Failed or dont have acount'
            ], 409);
        }
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
        if ( (Auth::user()->role != 'basic') || (Auth::user()->id == $data->user_id) ){
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
        if ( Auth::user()->role != 'basic' ){
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
            if (Auth::user()->id != $id ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access to that resource is forbidden!'
                ], 403);
            } else {
                //get user profile 
                $user_auth = User::findOrFail($id)->get();
                $data = [];
                $data_user = [];
                
                foreach ($user_auth as $dataAuth){
                    $data['id'] = $dataAuth->id;
                    $data['nama'] = $dataAuth->nama;
                    $data['email'] = $dataAuth->email;
                    $data['no_kontak'] = $dataAuth->user_profile->no_kontak;
                    $data['alamat'] = $dataAuth->user_profile->alamat;
                    $data['no_rekening'] = $dataAuth->user_profile->no_rekening;
                    $data['bank'] = $dataAuth->user_profile->bank;
                    array_push ( $data_user, $data);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User Profile',
                'data user' => $data_user,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'user not found!'
            ], 404);
        }

    }
    

    public function updateUser(Request $request)
    {
        $this->validate($request, [
            'no_kontak' => 'required',
            'alamat' => 'required|string',
            'no_rekening' => 'required',
            'bank' => 'required|string',
        ]);

        try {
            $updateProfile = Profile::where('user_id', Auth::user()->id)->first();

            if (!$updateProfile){
                $updateProfile = new Profile;
                $updateProfile->user_id = Auth::user()->id;
            }
            $updateProfile->no_kontak = $request->no_kontak;
            $updateProfile->alamat = $request->alamat;
            $updateProfile->no_rekening = $request->no_rekening;
            $updateProfile->bank = $request->bank;

            //Cek duplicate input data
            $existing_data = $updateProfile->where( 'no_kontak', $updateProfile->no_kontak )->first();
            $existing_noreq = $updateProfile->where( 'no_rekening', $updateProfile->no_rekening )->first();
            if ( $existing_data || $existing_noreq ) {
                return response()->json([
                    'success' => false,
                    'message' => 'user already used',
                    'data' => $existing_data, 
                    'data' => $existing_noreq,
                    'data' => $updateProfile,

                ], 425);
            } else {

                $updateProfile->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully update profile',
                'user' => $updateProfile, 
                ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update profile Failed or dont have acount'
            ], 409);
        }
    }


}