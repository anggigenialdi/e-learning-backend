<?php

namespace App\Http\Controllers;

use App\Models\Kursus_saya;
use Illuminate\Http\Request;

class KursusSayaController extends Controller
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

    public function postKursusSaya(Request $request) {
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id' => 'required|string',
        ]);

        try{

            $addKursusSaya = new Kursus_saya;
            $addKursusSaya->user_id = $request->user_id;
            $addKursusSaya->kursus_id = $request->kursus_id;
            
            // Cek duplikat data
            $duplicate_data = $addKursusSaya->where( 'kursus_id', $addKursusSaya->kursus_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $addKursusSaya,

                ], 425);
            } else {

                $addKursusSaya->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Kursus Saya',
                'data' => $addKursusSaya, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }
}
