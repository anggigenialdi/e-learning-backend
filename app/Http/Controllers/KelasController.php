<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kelas_selesai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
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

    public function postKelas(Request $request){

        $this->validate($request, [
            'kursus_id'  => 'required|string',
            'judul' => 'required|string|min:2',
            'posisi' => 'required|string',
        ]);

        try{

            if ( Auth::user()->role != 'basic') {

                $addKelas = new Kelas;
                $addKelas->kursus_id = $request->kursus_id;
                $addKelas->judul = $request->judul;
                $addKelas->posisi = $request->posisi;
                
                // Cek duplikat data
                $duplicate_data = $addKelas->where( 'judul', $addKelas->judul )->first();
                if ( $duplicate_data ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate data',
                        'data' => $addKelas,

                    ], 425);
                } else {

                    $addKelas->save();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully complete Kelas',
                    'data' => $addKelas, 
                ], 201);
            }

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateKelas(Request $request, $id){
        $this->validate($request, [
            'kursus_id'  => 'required|string',
            'judul' => 'required|string|min:2',
            'posisi' => 'required|string',
        ]);

        try{

            if ( Auth::user()->role != 'basic') {

                $updateKelas= Kelas::find($id);

                $updateKelas->kursus_id = $request->kursus_id;
                $updateKelas->judul = $request->judul;
                $updateKelas->posisi = $request->posisi;

                $updateKelas->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Update Kelas',
                    'data' => $updateKelas, 
                ], 201);
            }

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }

    }


    //Kelas Selesai
    public function postKelasSelesai(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kelas_id' => 'required|string',
            'kursus_id' => 'required|string',
            'materi_id' => 'required|string',
        ]);

        try{

            $addKelasSelesai = new Kelas_selesai;
            $addKelasSelesai->user_id = $request->user_id;
            $addKelasSelesai->kursus_id = $request->kursus_id;
            $addKelasSelesai->materi_id = $request->materi_id;
            $addKelasSelesai->kelas_id = $request->kelas_id;
            
            // Cek duplikat data
            $duplicate = $addKelasSelesai->where( 'kelas_id', $addKelasSelesai->kelas_id )->where( 'materi_id', $addKelasSelesai->materi_id )->where( 'user_id', $addKelasSelesai->user_id )->first();
            if ( $duplicate ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data'

                ], 425);
            } else {
                $addKelasSelesai->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Kelas Selesai',
                'data' => $addKelasSelesai, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateKelasSelesai(Request $request, $id) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kelas_id' => 'required|string'
        ]);

        try{

            $updateKelasSelesai = Kelas_selesai::find($id);
            $updateKelasSelesai->user_id = $request->user_id;
            $updateKelasSelesai->kelas_id = $request->kelas_id;
            
            // Cek duplikat data
                $updateKelasSelesai->save();
            return response()->json([
                'success' => true,
                'message' => 'Successfully update Kelas Selesai',
                'data' => $updateKelasSelesai, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }
}
