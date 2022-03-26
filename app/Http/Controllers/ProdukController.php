<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use \App\Models\Kategori;
use \App\Models\Satuan;
use \App\Models\Produk;

use DataTables;
use Validator;
use Custom;

class ProdukController extends Controller {

    public function index(Request $request) {
        if($request->ajax()) {
            $result = Produk::with('satuan','kategori');
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('harga', function($data){
                    return "Rp. ".Custom::conver_rupiah($data->harga);
                })
                ->addColumn('act', function($data) {
                    $btn_act = "<div class='btn-group'>
                        <a href='".asset('produk/ubah/'.$data->id)."' class='btn btn-act btn-info'><span class='entypo-pencil'></span></a>
                        <a href='javascript:void(0)' onclick='delete_data(".$data->id.")' class='btn btn-act btn-danger'><span class='entypo-trash'></span></a>
                    </div>";
                    return $btn_act;
                })
                ->rawColumns(['act','image'])
                ->make(true);
        }
        
        return view('pages.produk.detail');
    }

    public function tambah() {
        $kategori   = Kategori::all();
        $satuan     = Satuan::all();
        return view('pages.produk.create', compact('kategori','satuan'));
    }

    public function ubah($id) {
        $kategori   = Kategori::all();
        $satuan     = Satuan::all();
        $data       = Produk::find($id);
        return view('pages.produk.update', compact('data','kategori','satuan'));
    }

    //=======================================
    //=======================================
    //=======================================
    
    public function create_data(Request $request) {
        $validates 	= [
            "kd_produk"     => "required|unique:app_produk",
            "nm_produk"     => "required",
            "satuan_id"     => "required",
            "kategori_id"   => "required",
            "harga"         => "required",
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
                'kd_produk'     => $request->kd_produk,
                'nm_produk'     => $request->nm_produk,
                'kategori_id'   => $request->kategori_id,
                'satuan_id'     => $request->satuan_id,
                'harga'         => Custom::conver_angka($request->harga),
                "user_id"       => auth()->user()->id,
            ];
            
            $result = Produk::create($data);

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
        $validates 	= [
            "kd_produk"    => "required|unique:app_produk,kd_produk,".$id,
            "nm_produk"     => "required",
            "satuan_id"     => "required",
            "kategori_id"   => "required",
            "harga"         => "required",
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
                'kd_produk'     => $request->kd_produk,
                'nm_produk'     => $request->nm_produk,
                'kategori_id'   => $request->kategori_id,
                'satuan_id'     => $request->satuan_id,
                'harga'         => Custom::conver_angka($request->harga),
                "user_id"       => auth()->user()->id,
            ];

            $result = Produk::find($id)->update($data);
            
            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
        try {
            $result = Produk::find($id)->delete();
            return response()->json(['status'=>'success', 'messages'=>'proses success'], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }


}
