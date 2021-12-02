<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getKomentar']]);
    }

    public function getKomentar($idKursus,$idKelas,$idMateri){
            $komentar = Komentar::where('kursus_id', $idKursus)->where('kelas_id', $idKelas)->where('materi_id', $idMateri)->orderBy('created_at','desc')->paginate(3);
    
            if($komentar){
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'komentar berhasil diambil',
                            'data' => $komentar
                        ],
                        201
                    );
                } else {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'komentar gagal diambil',
                            'data' => '',
                        ],
                        400
                    );
                } 
    }

    public function postKomentar(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'materi_id' => 'required|string',
            'kursus_id' => 'required|string',
            'kelas_id' => 'required|string',
            'isi_komentar' => 'required|string',
        ]);

        try{

            $addKomentar = new Komentar;
            $addKomentar->user_id = $request->user_id;
            $addKomentar->materi_id = $request->materi_id;
            $addKomentar->kursus_id = $request->kursus_id;
            $addKomentar->kelas_id = $request->kelas_id;
            $addKomentar->isi_komentar = $request->isi_komentar;
            
            // Cek duplikat data
                $addKomentar->save();
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Kometar',
                'data' => $addKomentar, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateKomentar(Request $request, $id) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'materi_id' => 'required|string',
            'kursus_id' => 'required|string',
            'kelas_id' => 'required|string',
            'isi_komentar' => 'required|string',
        ]);

        try{

            $updateKomentar = Komentar::find($id);
            $updateKomentar->user_id = $request->user_id;
            $updateKomentar->materi_id = $request->materi_id;
            $updateKomentar->kelas_id = $request->kelas_id;
            $updateKomentar->kursus_id = $request->kursus_id;
            $updateKomentar->isi_komentar = $request->isi_komentar;
            $updateKomentar->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Kometar',
                'data' => $updateKomentar, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }
}
