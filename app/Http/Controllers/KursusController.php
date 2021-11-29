<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Kelas;
use App\Models\Instruktur;
use App\Models\Kursus_saya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;

class KursusController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','detailKursus']]);
    }

    public function index(){
        $kursus = Kursus::orderBy('created_at', 'asc')->get();

        $data = [];
        $data_kursus = [];
        
        $no = 0;
        foreach ($kursus as $kur) {
            $no++;
            $data['id_kursus'] = $kur->id;
            $data['nama_instruktur'] = $kur->instruktur->nama;
            $data['nama_kursus'] = $kur->judul_kursus;
            $data['foto_instruktur'] = $kur->instruktur->foto;
            $data['foto_kursus'] = $kur->foto;
            $data['harga'] = $kur->harga_kursus;
            $data['tipe_kursus'] = $kur->tipe_kursus;
            array_push($data_kursus, $data);
        }
        if ($kursus) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'kursus berhasil diambil',
                    'data' => $data_kursus,
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'kursus gagal diambil',
                    'data' => '',
                ],
                400
            );
        }
    }
    public function detailKursusSaya($idKursus,$idUser){
        $kursus_saya = Kursus_saya::where('kursus_id', $idKursus)->where('user_id', $idUser)->first();

        $kursus = Kursus::where('id', $idKursus)->get();
        $kelas = Kelas::where('kursus_id', $idKursus)->get();

        if($kursus_saya){
            $data = [];
            $data_kursus = [];
            
            $no = 0;
            foreach ($kursus as $kur) {
                $no++;
                $data['nama_instruktur'] = $kur->instruktur->nama;
                $data['nama_kursus'] = $kur->judul_kursus;
                $data['foto_instruktur'] = $kur->instruktur->foto;
                $data['foto_kursus'] = $kur->foto;
                $data['harga'] = $kur->harga_kursus;
                $data['tipe_kursus'] = $kur->tipe_kursus;
                array_push($data_kursus, $data);
            }
            foreach($kelas as $kel){
                $data['materi'] = $kel->materi;
            }
            if ($kursus) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'kursus berhasil diambil',
                        'data' => [
                            'data' => $data_kursus,
                            'data_kelas' =>$kelas,
                            ]
                            ,
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'kursus gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
        }   
        else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'kursus gagal diambil',
                    'data' => '',
                ],
                400
            );
        }
    }
    public function detailKursus($id){

        $kursus = Kursus::where('id', $id)->get();
        $kelas = Kelas::where('kursus_id', $id)->get();

            $data = [];
            $data_kursus = [];
            
            $no = 0;
            foreach ($kursus as $kur) {
                $no++;
                $data['nama_instruktur'] = $kur->instruktur->nama;
                $data['nama_kursus'] = $kur->judul_kursus;
                $data['foto_instruktur'] = $kur->instruktur->foto;
                $data['foto_kursus'] = $kur->foto;
                $data['harga'] = $kur->harga_kursus;
                $data['tipe_kursus'] = $kur->tipe_kursus;
                array_push($data_kursus, $data);
            }
            if ($kursus) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'kursus berhasil diambil',
                        'data' => $data_kursus,
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'kursus gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
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
            $duplicate_data = $add_Kursus->where( 'judul_kursus', $add_Kursus->judul_kursus )->first();
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
                'data' => $add_Kursus, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
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

    public function updateKursus(Request $request, $id){
        
        $this->validate($request, [
            'instruktur_id'  => 'required|string',
            'judul_kursus'  => 'required|string',
            'harga_kursus' => 'required|string',
            'tipe_kursus' => 'required|string',
            // 'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $updateKursus= Kursus::find($id);


        $updateKursus->instruktur_id = $request->instruktur_id;
        $updateKursus->judul_kursus = $request->judul_kursus;
        $updateKursus->harga_kursus = $request->harga_kursus;
        $updateKursus->tipe_kursus = $request->tipe_kursus;

        try {
            //Cek duplicate input data
            $duplicate_data = $updateKursus->where( 'judul_kursus', $updateKursus->judul_kursus )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $updateKursus,

                ], 425);
            } else {

                $updateKursus->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Kursus',
                'data' => $updateKursus, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }
    }


}