<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\User;
use Validator;
use Custom;

class LoginController extends Controller {
    public function index() {
        return view('login');
    }
    
    public function login(Request $request) {
        $validates 	= [
            "username"  => "required",
            "password"  => "required",
        ];
        $validation = Validator::make($request->all(), $validates, Custom::messages(), []);
        if($validation->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validation->errors()->first()
            ], 422);
        }
        
        try {
            $credentials = $request->only('username', 'password');
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(["status" => "error", 'messages' => 'Unauthorized'], 422);
            }

            return response()->json(['status'=>'success', 'messages'=>'proses success', 'data'=>$token], 201);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo ], 500);
        }  
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }

}
