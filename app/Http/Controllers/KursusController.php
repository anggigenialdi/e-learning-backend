<?php

namespace App\Http\Controllers;

use App\Models\Kursus;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Rating_kursus;
use App\Models\Kursus_aktif;
use App\Models\Kursus_saya;
use App\Models\Kelas_selesai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Environment\Console;
use DB;
class KursusController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','detailKursus', 'getRating','detailKursusSaya']]);
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
        // $kursus_saya = DB::table('kursuses as ks')
        // ->join('kursus_sayas as kursa', 'kursa.kursus_id', 'ks.id')->join('materis as mat','mat.kelas_id','ks.id')->join('kelas_selesais as kls_s','kls_s.kursus_id','ks.id')->where('kursa.user_id',$idUser)
        // ->select(
        //     'ks.*',
        //     'mat.*',
        //     'kls_s.*'
        // )
        // ->orderBy('ks.id', 'asc')->get();

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
                foreach ($kel->materi as $materi) {
                   $materi->kelas_selesai;
                }
            }

            if ($kursus) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'kursus berhasil diambil',
                        'data' => [
                            'data' => $kursus_saya,
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
            'harga_kursus' => 'required|numeric',
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

    // rating kursus
    public function postRatingKursus(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id' => 'required|string',
            'rating' => 'required|integer|min:1',
            'review' => 'required|string',
        ]);

        try{

            $addRating = new Rating_kursus;
            $addRating->user_id = $request->user_id;
            $addRating->kursus_id = $request->kursus_id;
            $addRating->rating = $request->rating;
            $addRating->review = $request->review;
            
            // Cek duplikat data
            $duplicate = $addRating->where( 'kursus_id', $addRating->kursus_id )->first();
            if ( $duplicate ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data'
                ], 425);
            } else {
                $addRating->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Rating',
                'data' => $addRating, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateRatingKursus(Request $request, $id) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id' => 'required|string',
            'rating' => 'required|integer|min:1|in:1,2,3,4,5',
            'review' => 'required|string',
        ]);

        try{

            $updateRating = Rating_kursus::find($id);
            $updateRating->user_id = $request->user_id;
            $updateRating->kursus_id = $request->kursus_id;
            $updateRating->rating = $request->rating;
            $updateRating->review = $request->review;
            
            // Cek duplikat data
                $updateRating->save();
            return response()->json([
                'success' => true,
                'message' => 'Successfully update Rating',
                'data' => $updateRating, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function getRating($idKursus){

        $rating = Rating_kursus::where('kursus_id', $idKursus)->get();
        $rating_length = count($rating);
        
        $data = [];
        $data_rating = [];
        $total_rating = 0;
        foreach ($rating as $rat) {
            $rat->user->nama;
        }

        foreach ($rating as $rat){
            $total_rating = ( $total_rating + $rat->rating );
        }
        $data_rating = $total_rating/$rating_length;

        if($rating){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'rating berhasil diambil',
                        'data' =>[
                            'rating'=>$data_rating,
                            'data_rating'=>$rating,
                        ] 
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'rating gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }




}