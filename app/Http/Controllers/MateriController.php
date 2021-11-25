<?php

namespace App\Http\Controllers;


use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
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

    public function postMateri(Request $request){

        $this->validate($request, [
            'kelas_id'  => 'required|string',
            'judul' => 'required|string|min:2',
            'deskripsi' => 'required|string|min:2',
            'link_video' => 'required|string',
            'posisi' => 'required|string',
            'materi_sebelumnya' => 'required|string',
            'materi_selanjutnya' => 'required|string',
        ]);

        try{

            if ( Auth::user()->role != 'basic') {

                $addMateri = new Materi;
                $addMateri->kelas_id = $request->kelas_id;
                $addMateri->judul = $request->judul;
                $addMateri->deskripsi = $request->deskripsi;
                $addMateri->link_video = $request->link_video;
                $addMateri->posisi = $request->posisi;
                $addMateri->materi_sebelumnya = $request->materi_sebelumnya;
                $addMateri->materi_selanjutnya = $request->materi_selanjutnya;
                
                // Cek duplikat data
                $duplicate_data = $addMateri->where( 'kelas_id', $addMateri->kelas_id )->first();
                if ( $duplicate_data ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate data',
                        'data' => $addMateri,

                    ], 425);
                } else {

                    $addMateri->save();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully complete Materi',
                    'data' => $addMateri, 
                ], 201);
            }

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }


    }

    public function updateMateri(Request $request, $id){

        $this->validate($request, [
            'kelas_id'  => 'required|string',
            'judul' => 'required|string|min:2',
            'deskripsi' => 'required|string|min:2',
            'link_video' => 'required|string',
            'posisi' => 'required|string',
            'materi_sebelumnya' => 'required|string',
            'materi_selanjutnya' => 'required|string',
        ]);

        try{

            if ( Auth::user()->role != 'basic') {

                $addMateri = Materi::find($id);

                $addMateri->kelas_id = $request->kelas_id;
                $addMateri->judul = $request->judul;
                $addMateri->deskripsi = $request->deskripsi;
                $addMateri->link_video = $request->link_video;
                $addMateri->posisi = $request->posisi;
                $addMateri->materi_sebelumnya = $request->materi_sebelumnya;
                $addMateri->materi_selanjutnya = $request->materi_selanjutnya;
                
                $addMateri->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Update Materi',
                    'data' => $addMateri, 
                ], 201);
            }

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Access to that resource is forbidden'
            ], 403);
        }


    }

}
