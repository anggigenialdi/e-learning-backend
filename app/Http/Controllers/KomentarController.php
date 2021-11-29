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
        $this->middleware('auth');
    }

    public function postKomentar(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'materi_id' => 'required|string',
            'isi_komentar' => 'required|string',
        ]);

        try{

            $addKomentar = new Komentar;
            $addKomentar->user_id = $request->user_id;
            $addKomentar->materi_id = $request->materi_id;
            $addKomentar->isi_komentar = $request->isi_komentar;
            
            // Cek duplikat data
            $duplicate_data = $addKomentar->where( 'user_id', $addKomentar->user_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah memberikan komentar',
                    'data' => $addKomentar,

                ], 425);
            } else {

                $addKomentar->save();
            }
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
            'isi_komentar' => 'required|string',
        ]);

        try{

            $updateKomentar = Komentar::find($id);
            $updateKomentar->user_id = $request->user_id;
            $updateKomentar->materi_id = $request->materi_id;
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
