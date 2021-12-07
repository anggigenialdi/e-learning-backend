<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\Materi;
use App\Models\Terakhir_ditonton;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TerakhirDitontonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postTerakhirDitonton(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id' => 'required|string',
            'kelas_id' => 'required|string',
            'materi_id' => 'required|string',
        ]);

        try{

            $terakhirDitonton = new Terakhir_ditonton;
            $terakhirDitonton->user_id = $request->user_id;
            $terakhirDitonton->kursus_id = $request->kursus_id;
            $terakhirDitonton->kelas_id = $request->kelas_id;
            $terakhirDitonton->materi_id = $request->materi_id;

            $duplicate_data = $terakhirDitonton->where( 'kursus_id', $terakhirDitonton->kursus_id)->where('user_id',$terakhirDitonton->user_id)->where( 'kelas_id', $terakhirDitonton->kelas_id)->where( 'materi_id', $terakhirDitonton->materi_id)->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $terakhirDitonton,

                ], 425);
            } else {
                $terakhirDitonton->save();
            }


            return response()->json([
                'success' => true,
                'message' => 'Successfully',
                'data' => $terakhirDitonton, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateTerakhirDitonton($id, Request $request){

        $updateData = Terakhir_ditonton::where('id', $id)->first();

        $updateData->update([
            'user_id' => $request->input('user_id'),
            'kursus_id' => $request->input('kursus_id'),
            'kelas_id' => $request->input('kelas_id'),
            'materi_id' => $request->input('materi_id'),
        ]);

        if($updateData){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'berhasil',
                        'data' =>[
                            'data_history'=>$updateData,
                        ] 
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'gagal',
                        'data' => '',
                    ],
                    400
                );
            } 
    }
    

    public function getTerakhirDitonton($idUser, $idKursus){

        $history = Terakhir_ditonton::where('user_id', $idUser)->where('kursus_id', $idKursus)->first();
                
        $user = User::where('id', $idUser)->get();
        $kursus = Kursus::where('id', $idKursus)->get();
        $kelas = Kelas::where('kursus_id', $idKursus)->get();

        if($history){
            $data = [];
            $data_kursus = [];
            $data_user = [];
            $data_mtr = [];
            

            foreach ($user as $us) {
                $datas['id_user'] = $us->id;
                $datas['nama_user'] = $us->nama;
                $datas['email'] = $us->email;
                array_push($data_user, $datas);
            }
            foreach ($kursus as $kur) {
                $data['id_kursus'] = $kur->id;
                $data['nama_instruktur'] = $kur->instruktur->nama;
                $data['nama_kursus'] = $kur->judul_kursus;
                $data['foto_instruktur'] = $kur->instruktur->foto;
                $data['foto_kursus'] = $kur->foto;
                $data['harga'] = $kur->harga_kursus;
                $data['tipe_kursus'] = $kur->tipe_kursus;
                array_push($data_kursus, $data);
            }
            foreach($kelas as $kel){
                $kel->materi;
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'history berhasil diambil',
                    'data' =>[
                        'data_user'=>$data_user,
                        'data_kursus'=>$data_kursus,
                        'data_kelas'=>$kelas,
                        'data_materi'=>$data_mtr,
                    ] 
                ],
                201
            );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => ' gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }
}
