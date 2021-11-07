<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class InstrukturController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postInstruktur(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|string',
            'keterangan' => 'required|string',
            'foto' => 'required|image',
        ]);
        $avatar = Str::random(34);
        $request->file('foto')->move(storage_path('avatar'), $avatar);

        $data = Instruktur::firstOrNew([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'foto' => $request->foto,
        ]);

        // Hak akses input data 
        if ( (Auth::user()->role != 'basic') ){
            try {

                //Cek duplicate input data
                $existing_data = $data->where('nama', $data->nama)->first();
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
                        'message' => 'Successfully complete Instruktur',
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
                    'message' => 'Complete Instruktur Failed or dont have acount'
                ], 409);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
    }



}