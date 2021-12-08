<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\Materi;
use App\Models\Terakhir_ditonton;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
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
            
            $terakhirDitonton->save();


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
        // $history = DB::table('kursuses as ks')
        // ->join('terakhir_ditontons as td', 'td.kursus_id', 'ks.id')
        // ->join('kelas as kls','kls.kursus_id','ks.id')
        // ->join('materis as mat','mat.kelas_id','ks.id')
        // ->where('td.user_id',$idUser)
        // ->where('td.kursus_id',$idKursus)
        // // ->where('td.materi_id','mat.id')
        // ->select(
        //     'ks.*',
        //     'kls.*',
        //     'mat.*',
        // )->get();
                

        if($history){
            $materi = Materi::where('kelas_id',$history->kelas_id)->where('id',$history->materi_id)->get();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'history berhasil diambil',
                    'data' =>[
                        'history'=>$materi,
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
