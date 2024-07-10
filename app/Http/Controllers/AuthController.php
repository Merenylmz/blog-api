<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Jerry\JWT\JWT;

class AuthController extends Controller
{
    public function login(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if (!$user) {return response()->json(["status"=>"Is Not OK", "msg"=> "User is not found"]);}
            if (!Hash::check($req->input("password"), $user->password)) {
                return response()->json(["status"=>"Is Not OK", "msg"=> "Wrong password"]); 
            } 

            $token = JWT::encode(["userId"=>$user->id, "isitAdmin"=>$user->isitAdmin]);
            Cache::put("loginToken:{$user->id}", $token, 30);
            $user->lastLoginToken = $token;
            $user->save();


            return response()->json(["status"=>"OK", "token"=>$token]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
        }
    }

    public function register(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if ($user) {
                return response()->json(["status"=>"Is Not OK", "msg"=>"User is already exists"]);
            }

            $newUser = new User();
            $newUser->name = $req->input("name");
            $newUser->email = $req->input("email");
            $newUser->password = Hash::make($req->input("password"));
            $newUser->save();

            return response()->json(["status"=>"OK", "msg"=>"Welcome, {$newUser->name}"]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
        }
    }

    public function editUser(Request $req){
        try {
            $user = User::find($req->attributes->get("userId"));
            $user->bioTxt = $req->input("biotxt");
            if ($req->hasFile("profileUrl")) {
                $profileUrlFile = $req->file("profileUrl");
                $profileUrlFileName = "profilephoto_".$user->id."_".time()."_".$profileUrlFile->getClientOriginalName();
                $profileUrlFile->move(public_path("profilePhoto"), $profileUrlFileName);
                $profileUrl = url("profilePhoto", $profileUrlFileName);
                $user->profilePhoto = $profileUrl;
            }
            if ($req->has("biotxt")) {
                $user->bioTxt = $req->input("biotxt");
            }
            $user->save();

            return response()->json(["status"=> "OK", "profilePhoto"=>$user->profilePhoto, "bioTxt"=>$user->bioTxt]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
        }
    }
   
}
