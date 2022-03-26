<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\User;

use DataTables;
use Validator;
use Custom;

class UserController extends Controller {
    public function index(Request $request) {
        if($request->ajax()) {
            $result = User::query();
            return DataTables::of($result)
                ->addIndexColumn()
                ->addColumn('act', function($data) {
                    $btn_act = "<div class='btn-group'>
                        <a href='".asset('user/ubah/'.$data->id)."' class='btn btn-act btn-info'><span class='entypo-pencil'></span></a>
                        <a href='javascript:void(0)' onclick='delete_data(".$data->id.")' class='btn btn-act btn-danger'><span class='entypo-trash'></span></a>
                    </div>";
                    return $btn_act;
                })
                ->rawColumns(['act'])
                ->make(true);
        }
        
        return view('pages.user.detail');
    }

    public function tambah() {
        return view('pages.user.create');
    }

    public function ubah($id) {
        $data   = User::find($id);
        return view('pages.user.update', compact('data'));
    }

    public function create_data(Request $request) {
        $validates 	= [
            "nm_lengkap"=> "required",
            "username"  => "required|unique:app_user",
            "password"  => "required",
            "level"     => "required"
        ];

        $atribut = [
            "nm_lengkap"    => "nama lengkap",
        ];
        

        $validation = Validator::make($request->all(), $validates, Custom::messages(), $atribut);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [ 
                "nm_lengkap"    => $request->nm_lengkap,
                "username"      => $request->username,
                "password"      => bcrypt($request->password),
                "level"         => $request->level,
            ];

            $result = User::create($data);

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function update_data(Request $request, $id) {
        $validates 	= [
            "nm_lengkap"=> "required",
            "username"  => "required|unique:app_user,username,".$id,
            "level"     => "required"
        ];

        $atribut = [
            "nm_lengkap"    => "nama lengkap",
        ];
        

        $validation = Validator::make($request->all(), $validates, Custom::messages(), $atribut);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }

        try {
            $data = [ 
                "nm_lengkap"    => $request->nm_lengkap,
                "username"      => $request->username,
                "level"         => $request->level,
            ];

            if(!empty($request->password)) {
                $data += ["password" => bcrypt($request->password)];
            }

            $result = User::find($id)->update($data);

            return response()->json(['status'=>'success','messages'=>'proses success'], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }
    }

    public function delete_data($id) {
        try {
            $result = User::find($id)->delete();
            
            return response()->json(['status'=>'success', 'messages'=>'proses success'], 201);
        } catch(QueryException $e) {
            return response()->json(['status'=>'error', 'messages'=>$e->errorInfo ], 500);
        }
    }
}
