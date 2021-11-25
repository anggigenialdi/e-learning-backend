<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
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
                $duplicate_data = $addKelas->where( 'kursus_id', $addKelas->kursus_id )->first();
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
                'message' => 'Access to that resource is forbidden'
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
                
                // Cek duplikat data
                $duplicate_data = $updateKelas->where( 'kursus_id', $updateKelas->kursus_id )->first();
                if ( $duplicate_data ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate data',
                        'data' => $updateKelas,

                    ], 425);
                } else {

                    $updateKelas->save();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully Update Kelas',
                    'data' => $updateKelas, 
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
