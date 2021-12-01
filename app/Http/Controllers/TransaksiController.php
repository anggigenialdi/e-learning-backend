<?php

namespace App\Http\Controllers;


use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Laravel\Lumen\Routing\Controller as BaseController;

class TransaksiController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postTransaksi(Request $request){

        $this->validate($request, [
            'user_id'  => 'required|string',
            'kursus_id'  => 'required|string',
            'total_price'  => 'required|numeric',
            'tanggal_pembelian'  => 'required|date',
            'status_transaksi'  => 'required|string',
        ]);

        try{

            $addTransaksi = new Transaksi;
            $addTransaksi->user_id = $request->user_id;
            $addTransaksi->kursus_id = $request->kursus_id;
            $addTransaksi->total_price = $request->total_price;
            $addTransaksi->tanggal_pembelian = $request->tanggal_pembelian;
            $addTransaksi->status_transaksi = $request->status_transaksi;
            
            // Cek duplikat data
            $duplicate_data = $addTransaksi->where( 'kursus_id', $addTransaksi->kursus_id )->first();
            if ( $duplicate_data ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate data',
                    'data' => $addTransaksi,

                ], 425);
            } else {

                $addTransaksi->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Successfully complete Transaksi',
                'data' => $addTransaksi, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
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
}
