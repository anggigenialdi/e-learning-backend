<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            'nama'  => 'required|string',
            'keterangan' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $add_Instruktur = new Instruktur;

        if ($request->hasFile('foto')){
            $file = $request->file('foto');
            $allowedFileExtention = ['png','jpeg','jpg','svg','gif','PNG'];
            $extention = $file->getClientOriginalExtension();
            $check = in_array($extention, $allowedFileExtention);

            if($check) {
                $name = time().'.'.$file->getClientOriginalExtension();
                $file->move(storage_path('/public/foto-instruktur', $name));
                $add_Instruktur->foto = $name;
            }
        }
        $add_Instruktur->nama = $request->nama;
        $add_Instruktur->keterangan = $request->keterangan;

        // $add_Instruktur->save();

        try {
            //Cek duplicate input data
            $duplicate_data = $add_Instruktur->where( 'nama', $add_Instruktur->nama )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $add_Instruktur,

                ], 425);
            } else {

                $add_Instruktur->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Instruktur',
                'user' => $add_Instruktur, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
    }

    public function getFoto($name)
    {
        $foto_path = storage_path('/public/foto-instruktur') . '/' . $name;
        if (file_exists($foto_path)) {
            $file = file_get_contents($foto_path);
            return response($file, 200)->header('Content-Type', 'image/jpeg');
            }
        $res['success'] = false;
            $res['message'] = "Foto not found";
        
        return $res;
    }

    public function getAllInstruktur()
    {
        if (Auth::user()->role != 'basic'){
            return response()->json([
                'success' => true,
                'message' => 'List All Instruktur',
                'data' =>  Instruktur::OrderBy('id', 'DESC')->paginate(2),
            ], 200);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
        
    }

    public function getInstruktur($id)
    {
        try {
            $instruktur = Instruktur::findOrFail($id);

            //cek user login jika bukan maka data tidak akan ditampilkan
            if ( Auth::user()->role != 'basic') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Instruktur',
                    'data' => ([
                        $instruktur
                    ])
                ], 200);
            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Access to that resource is forbidden!'
                ], 403);
            }           

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'user not found!'
            ], 404);
        }

    }

    public function updateInstruktur(Request $request, $id){
        
        $this->validate($request, [
            'nama'  => 'required|string',
            'keterangan' => 'required|string',
            // 'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $updateInstruktur= Instruktur::find($id);


        $updateInstruktur->nama = $request->nama;
        $updateInstruktur->keterangan = $request->keterangan;

        try {
            //Cek duplicate input data
            $duplicate_data = $updateInstruktur->where( 'nama', $updateInstruktur->nama )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $updateInstruktur,

                ], 425);
            } else {

                $updateInstruktur->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Instruktur',
                'user' => $updateInstruktur, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
    }



}