<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kursus;
use App\Models\Materi;
use App\Models\Terakhir_ditonton;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
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

    public function postVoucher(Request $request) {
        $this->validate($request, [
            'kode'  => 'required|string',
            'potongan' => 'required|string',
        ]);

        try{

            $addVoucher = new Voucher;
            $addVoucher->kode = $request->kode;
            $addVoucher->potongan = $request->potongan;
            $addVoucher->save();


            return response()->json([
                'success' => true,
                'message' => 'Successfully',
                'data' => $addVoucher, 
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 403);
        }


    }

    public function updateVoucher($idVoucher, Request $request){

        $updateData = Voucher::where('id', $idVoucher)->first();

        $updateData->update([
            'kode' => $request->input('kode'),
            'potongan' => $request->input('potongan'),
        ]);

        if($updateData){
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'update berhasil',
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
    

    public function getVoucher($idVoucher){

        $voucher = Voucher::where('id', $idVoucher)->first();
                

        if($voucher){
            return response()->json(
                [
                    'success' => true,
                    'message' => 'voucher berhasil diambil',
                    'data' =>[
                        'voucher'=>$voucher,
                    ] 
                ],
                201
            );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }

    public function postCodeVoucher(Request $request){

        $voucher = Voucher::where('kode', $request->input('kode'))->first();
                

        if($voucher){
            $data_voucher = [ 
                'kode' => $voucher->kode, 
                'potongan' => $voucher->potongan, 
            ];
            return response()->json(
                [
                    'success' => true,
                    'message' => 'voucher berhasil diambil',
                    'data' =>[
                        'voucher'=>$data_voucher,
                    ] 
                ],
                201
            );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'gagal diambil',
                        'data' => '',
                    ],
                    400
                );
            } 
    }
}
