<?php

namespace App\Http\Controllers;


use App\Models\Kursus;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

use Carbon\Carbon as time;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class TransaksiController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getTransaksi', 'updateTransaksiStatus']]);
    }

    public function postTransaksi($idUser, $idKursus){

        try{

            $user = User::where('id', $idUser)->first();
            $kursus = Kursus::where('id', $idKursus)->first();
            

            $addTransaksi = new Transaksi;
            $addTransaksi->user_id = $user->id;
            $addTransaksi->kursus_id = $kursus->id;
            $addTransaksi->total_price = $kursus->harga_kursus;
            $addTransaksi->tanggal_pembelian = time::now();
            $addTransaksi->status_transaksi = 'menunggu';


            // Cek duplikat data
            $duplicate_data = $addTransaksi->where('user_id', $addTransaksi->user_id)->where( 'kursus_id', $addTransaksi->kursus_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $addTransaksi,

                ], 425);
            } else {
                $addTransaksi->save();


                return response()->json([
                    'success' => true,
                    'message' => 'Successfully complete Transaksi',
                    'data' => $addTransaksi, 
                ], 201);
            }
            

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e, 
            ], 403);
        }


    }

    public function updateTransaksi(Request $request, $id){

        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id'  => 'required|string',
            'total_price'  => 'required|numeric',
            'tanggal_pembelian'  => 'required|date',
            'status_transaksi'  => 'required|string',
        ]);

        try{

            $updateTransaksi = Transaksi::find($id);;
            $updateTransaksi->user_id = $request->user_id;
            $updateTransaksi->kursus_id = $request->kursus_id;
            $updateTransaksi->total_price = $request->total_price;
            $updateTransaksi->tanggal_pembelian = $request->tanggal_pembelian;
            $updateTransaksi->status_transaksi = $request->status_transaksi;
            
            // Cek duplikat data
            $duplicate_data = $updateTransaksi->where( 'kursus_id', $updateTransaksi->kursus_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $updateTransaksi,

                ], 425);
            } else {

                $updateTransaksi->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully Update Transaksi',
                'data' => $updateTransaksi, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function getTransaksi($idUser, $idKursus){

        $transaksi = Transaksi::where('user_id', $idUser)->where('kursus_id', $idKursus)->where('status_transaksi', '=', 'menunggu')->get();
        
        $data = [];
        $data_transaksi = [];
        $data['total'] = 0;

        foreach ($transaksi as $tra){
            $data['total'] = (  $data['total'] + $tra->total_price );
        }

        array_push ( $data_transaksi, $data);

        if($transaksi){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'transaksi berhasil diambil',
                        'data' =>[
                            'transaksi'=>$data_transaksi,
                            'data_transaksi'=>$transaksi,
                        ] 
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'transaksi gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }

    public function updateTransaksiStatus($idUser, $idKursus, Request $request){

        $transaksi = Transaksi::where('user_id', $idUser)->where('kursus_id', $idKursus)->first();

        $transaksi->update(['status_transaksi' => 'aktif']);

        if($transaksi){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'status transaksi berhasil diambil',
                        'data' =>[
                            'data_transaksi'=>$transaksi,
                        ] 
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'transaksi gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }


}
