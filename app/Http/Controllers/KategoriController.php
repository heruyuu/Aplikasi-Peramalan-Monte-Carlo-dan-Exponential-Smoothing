<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use \App\Models\Kategori;

use DataTables;
use Validator;
use Custom;

class KategoriController extends Controller {
    public function index(Request $request) {
        if($request->ajax()) {
            $result = Kategori::query();
            return DataTables::of($result)
                ->addIndexColumn()
                ->addColumn('act', function($data) {
                    $btn_act = "<div class='btn-group'>
                        <a href='".asset('kategori/ubah/'.$data->id)."' class='btn btn-act btn-info'><span class='entypo-pencil'></span></a>
                        <a href='javascript:void(0)' onclick='delete_data(".$data->id.")' class='btn btn-act btn-danger'><span class='entypo-trash'></span></a>
                    </div>";
                    return $btn_act;
                })
                ->rawColumns(['act'])
                ->make(true);
        }
        
        return view('pages.kategori.detail');
    }

    public function tambah() {
        return view('pages.kategori.create');
    }

    public function ubah($id) {
        $data = Kategori::find($id);
        return view('pages.kategori.update', compact('data'));
    }

    //=======================================
    //=======================================
    //=======================================
    
    public function create_data(Request $request) {
        $validates 	= [
            "kategori"  => "required|unique:app_kategori",
        ];

        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = ["kategori" => $request->kategori, "user_id" => auth()->user()->id, ];
            $result = Kategori::create($data);
            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
        $validates 	= [
            "kategori"    => "required|unique:app_kategori,kategori,".$id,
        ];
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data   = ["kategori" => $request->kategori, "user_id" => auth()->user()->id, ];
            $result = Kategori::find($id)->update($data);

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
        try {
            $result = Kategori::find($id)->delete();
            return response()->json(['status'=>'success', 'messages'=>'proses success'], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }


}
