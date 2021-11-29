<?php

namespace App\Http\Controllers;

use App\Models\Kursus_saya;
use App\Models\Kursus;
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
        $this->middleware('auth', ['except' => ['kursusSaya']]);
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
            $duplicate_data = $addKursusSaya->where( 'kursus_id', $addKursusSaya->kursus_id)->where('user_id',$addKursusSaya->user_id)->first();
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
    public function kursusSaya($id){

        $kursus = Kursus_saya::where('user_id', $id)->get();

        // foreach($kursus_saya as $k_saya){
        //     $kursus = Kursus::where('id', $k_saya->kursus_id)->get();

            $data = [];
            $data_kursus = [];
            
            $no = 0;
            foreach ($kursus as $kur) {
                $no++;
                $data['id_kursus'] = $kur->kursus->id;
                $data['nama_kursus'] = $kur->kursus->judul_kursus;
                $data['tipe_kursus'] = $kur->kursus->tipe_kursus;
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
        // }
    }
    public function updateKursusSaya(Request $request, $id){
        
        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id' => 'required|string',
        ]);

        $updateKursusSaya= Kursus_saya::find($id);


        $updateKursusSaya->user_id = $request->user_id;
        $updateKursusSaya->kursus_id = $request->kursus_id;

        try {
            //Cek duplicate input data
            $duplicate_data = $updateKursusSaya->where( 'kursus_id', $updateKursusSaya->kursus_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $updateKursusSaya,

                ], 425);
            } else {

                $updateKursusSaya->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Kursus',
                'data' => $updateKursusSaya, 
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }
    }
}
