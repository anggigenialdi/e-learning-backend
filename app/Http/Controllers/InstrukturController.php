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
            'nama'  => 'required|string',
            'keterangan' => 'required|string',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $add_Instruktur = new Instruktur;

        if ($request->hasFile('foto')){
            $file = $request->file('foto');
            $allowedFileExtention = ['png','jpeg','jpg','svg','gif'];
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

        $add_Instruktur->save();

        try {
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

    public function get_avatar($name)
    {
        $avatar_path = storage_path('/public/foto-instruktur') . '/' . $name;
    if (file_exists($avatar_path)) {
        $file = file_get_contents($avatar_path);
        return response($file, 200)->header('Content-Type', 'image/jpeg');
        }
    $res['success'] = false;
        $res['message'] = "Avatar not found";
        
        return $res;
    }


}