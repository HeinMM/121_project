<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createCustomer(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',

        ]);
        $user = User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>Hash::make($request->input('password')),

        ]);
        $token = $user->createToken('AuthApiToken')->accessToken;
        $responMessage =  array("status"=>true,"message"=>"User Created Successfully","token"=>$token);
        return response()->json([
            $responMessage
        ]);
    }

    public function index(){
       $users =  User::all();
       return $users->pluck('email','id_name');
    }

    public function redirect(){
        $info = array("message"=>"you need to Authotized");
        return response()->json([
            $info
        ]);
    }

    public function login(Request $request){
       $request->validate([
            'email'=>['required'],
            'password'=>'required'
        ]);
        //return $data;
        if (Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])) {

            /** @var \App\Models\User */
            $currentUser = Auth::user();
            $token = $currentUser->createToken('Auth')->accessToken;

            $isAdmin = DB::table('users')
            ->where('email',"=",$request->input('email'))->value('isAdmin');
            if ($isAdmin) {
                return response()->json([
                    'status'=>true,
                    'isAdmin'=>true,
                    'message'=>'Admin Login Successfully',
                    'token'=>$token
                ]
                );
            }
            else{
                return response()->json([
                    'status'=>true,
                    'isAdmin'=>false,
                    'message'=>'User Login Successfully',
                    'token'=>$token
                ]);
            }

        }else{
            return response()->json([
                'status'=>false,
                'isAdmin'=>false,
                'message'=>'User Login Fail',
                'token'=>''
            ]);
        }

    }
    public function logout(Request $request)
    {

        if (DB::table('users')
        ->where('email',"=",$request->input('email'))) {
            return response()->json([

                'status' => false,
                'isAdmin' => false,
                'message' => 'Admin logged out Successfully',
                'token' => ''

            ], );
        }

    }

}
