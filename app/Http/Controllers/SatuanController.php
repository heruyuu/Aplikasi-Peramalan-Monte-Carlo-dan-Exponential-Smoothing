<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use \App\Models\Satuan;

use DataTables;
use Validator;
use Custom;

class SatuanController extends Controller {
    public function index(Request $request) {
        if($request->ajax()) {
            $result = Satuan::query();
            return DataTables::of($result)
                ->addIndexColumn()
                ->addColumn('act', function($data) {
                    $btn_act = "<div class='btn-group'>
                        <a href='".asset('satuan/ubah/'.$data->id)."' class='btn btn-act btn-info'><span class='entypo-pencil'></span></a>
                        <a href='javascript:void(0)' onclick='delete_data(".$data->id.")' class='btn btn-act btn-danger'><span class='entypo-trash'></span></a>
                    </div>";
                    return $btn_act;
                })
                ->rawColumns(['act'])
                ->make(true);
        }
        
        return view('pages.satuan.detail');
    }

    public function tambah() {
        return view('pages.satuan.create');
    }

    public function ubah($id) {
        $data = Satuan::find($id);
        return view('pages.satuan.update', compact('data'));
    }

    //=======================================
    //=======================================
    //=======================================
    
    public function create_data(Request $request) {
        $validates 	= [
            "satuan"  => "required|unique:app_satuan",
        ];

        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = ["satuan" => $request->satuan, "user_id" => auth()->user()->id, ];
            $result = Satuan::create($data);
            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
        $validates 	= [
            "satuan"    => "required|unique:app_satuan,satuan,".$id,
        ];
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data   = ["satuan" => $request->satuan, "user_id" => auth()->user()->id, ];
            $result = Satuan::find($id)->update($data);

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
        try {
            $result = Satuan::find($id)->delete();
            return response()->json(['status'=>'success', 'messages'=>'proses success'], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }


}
