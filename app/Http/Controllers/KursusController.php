<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Instruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class KursusController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postKursus(Request $request)
    {
        $this->validate($request, [
            'instruktur_id'  => 'required|string',
            'judul_kursus' => 'required|string|min:2',
            'harga_kursus' => 'required|numeric|min:2',
            'tipe_kursus' => 'required|string|min:2',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $add_Kursus = new Kursus;

        if ($request->hasFile('foto')){
            $file = $request->file('foto');
            $allowedFileExtention = ['png','jpeg','jpg','svg','gif'];
            $extention = $file->getClientOriginalExtension();
            $check = in_array($extention, $allowedFileExtention);

            if($check) {
                $name = time().'.'.$file->getClientOriginalExtension();
                $file->move(storage_path('/public/foto-kursus', $name));
                $add_Kursus->foto = $name;
            }
        }
        $add_Kursus->instruktur_id = $request->instruktur_id;
        $add_Kursus->judul_kursus = $request->judul_kursus;
        $add_Kursus->harga_kursus = $request->harga_kursus;
        $add_Kursus->tipe_kursus = $request->tipe_kursus;
        // var_dump($add_Kursus);die();
        // $add_Kursus->save();

        try {
            //Cek duplicate input data
            $duplicate_data = $add_Kursus->where( 'instruktur_id', $add_Kursus->instruktur_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $add_Kursus,

                ], 425);
            } else {

                $add_Kursus->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Kursus',
                'user' => $add_Kursus, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }
    }

    public function get_avatar($name)
    {
        $avatar_path = storage_path('/public/foto-Kursus') . '/' . $name;
    if (file_exists($avatar_path)) {
        $file = file_get_contents($avatar_path);
        return response($file, 200)->header('Content-Type', 'image/jpeg');
        }
    $res['success'] = false;
        $res['message'] = "Avatar not found";
        
        return $res;
    }


}