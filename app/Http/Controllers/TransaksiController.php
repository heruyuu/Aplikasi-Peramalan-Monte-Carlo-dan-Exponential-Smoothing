<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use \App\Models\Transaksi;
use \App\Models\TransaksiBantu;


use DataTables;
use Validator;
use Custom;

class TransaksiController extends Controller {
    public function index(Request $request) {
        if($request->ajax()) {
            $result = Transaksi::query();
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('total_bayar', function($data){
                    return "Rp. ".Custom::conver_rupiah($data->total_bayar);
                })
                ->addColumn('act', function($data) {
                    $btn_crud ="";
                    if(auth()->user()->level!="pimpinan") {
                        $btn_crud = "<a href='".asset('transaksi/ubah/'.$data->id)."' class='btn btn-act btn-info'><span class='entypo-pencil'></span></a>
                            <a href='javascript:void(0)' onclick='delete_data(".$data->id.")' class='btn btn-act btn-danger'><span class='entypo-trash'></span></a>";
                    }
                    
                    $btn_act = "<div class='btn-group'>
                        <a href='javascript:void(0)' onclick='show_data(".$data->id.")' class='btn btn-act btn-success'><span class='entypo-eye'></span></a>
                        ".$btn_crud."
                    </div>";

                    return $btn_act;
                })
                ->rawColumns(['act'])
                ->make(true);
        }
        
        return view('pages.transaksi.detail');
    }

    public function tambah() {
        return view('pages.transaksi.create');
    }

    public function ubah($id) {
        $data     = Transaksi::find($id);
        return view('pages.transaksi.update', compact('data'));
    }

     //=======================================
    //=======================================
    //=======================================
    public function create_data(Request $request) {
        $validates 	= [
            "no_transaksi"  => "required|unique:app_transaksi",
            "tgl"           => "required",

            "produk_id"    => "required|array",
            "produk_id.*"  => "required"
        ];

        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [
                "no_transaksi"  => $request->no_transaksi, 
                "pembeli"       => $request->pembeli, 
                "tgl"           => $request->tgl, 
                "total_bayar"   => Custom::conver_angka($request->total_bayar),
                "user_id"       => auth()->user()->id
            ];

            $result = Transaksi::create($data);

            foreach ($request->produk_id as $key => $item_temp) {
                $data_bantu = [
                    'transaksi_id'  => $result->id,
                    'produk_id'     => $request->produk_id[$key],
                    'qty'           => Custom::conver_angka($request->qty[$key]),
                    'harga'         => Custom::conver_angka($request->harga[$key]),
                ];

                TransaksiBantu::create($data_bantu);
            }

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
        $validates 	= [
            "no_transaksi"  => "required|unique:app_transaksi,no_transaksi,".$id,
            "tgl"           => "required",
            "produk_id"     => "required|array",
            "produk_id.*"   => "required"
        ];

        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [
                "no_transaksi"  => $request->no_transaksi, 
                "pembeli"       => $request->pembeli,
                "tgl"           => $request->tgl, 
                "total_bayar"   => Custom::conver_angka($request->total_bayar),
                "user_id"       => auth()->user()->id
            ];

            $result = Transaksi::find($id)->update($data);
            TransaksiBantu::where('transaksi_id', $id)->delete();

            foreach ($request->produk_id as $key => $item_temp) {
                $data_bantu = [
                    'transaksi_id'  => $id,
                    'produk_id'     => $request->produk_id[$key],
                    'qty'           => Custom::conver_angka($request->qty[$key]),
                    'harga'         => Custom::conver_angka($request->harga[$key]),
                ];

                TransaksiBantu::create($data_bantu);
            }

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function show_data($id) {
        try {
            $result = Transaksi::with(['transaksi_bantu' => function($q){
                $q->with('produk', function($q){
                    $q->with('satuan','kategori');
                });
            }])->find($id);
            
            return response()->json(['status'=>'success', 'messages'=>'proses success', 'data'=>$result], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
        try {
            $result = Transaksi::find($id)->delete();
            return response()->json(['status'=>'success', 'messages'=>'proses success'], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }
}
