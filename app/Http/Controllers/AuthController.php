<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            Cache::put("loginToken:{$user->id}", $token, 60*60);
            $user->lastLoginToken = $token;
            $user->save();


            $response = ["status"=>"OK", "token"=>$token];
            if ($user->avatar_url != null) {
                $avatarUrl = Storage::url($user->avatar_url);
                array_push($response, url($avatarUrl));
            }
            return response()->json($response);
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
            if (Cache::has("allUsers")) {
                Cache::forget("allUsers");
            }

            return response()->json(["status"=>"OK", "msg"=>"Welcome, {$newUser->name}"]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
        }
    }

    // public function editUser(Request $req){
    //     try {
    //         $user = User::find($req->attributes->get("userId"));
    //         $user->bioTxt = $req->input("biotxt");
    //         if ($req->hasFile("profileUrl")) {
    //             $profileUrlFile = $req->file("profileUrl");
    //             $profileUrlFileName = "profilephoto_".$user->id."_".time()."_".$profileUrlFile->getClientOriginalName();
    //             $profileUrlFile->move(public_path("profilePhoto"), $profileUrlFileName);
    //             $profileUrl = url("profilePhoto", $profileUrlFileName);
    //             $user->profilePhoto = $profileUrl;
    //         }
    //         if ($req->has("biotxt")) {
    //             $user->bioTxt = $req->input("biotxt");
    //         }
    //         $user->save();

    //         return response()->json(["status"=> "OK", "profilePhoto"=>$user->profilePhoto, "bioTxt"=>$user->bioTxt]);
    //     } catch (\Throwable $th) {
    //         return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
    //     }
    // }

    

    public function logout(Request $req){
        try {
            $user = User::find($req->attributes->get("userId"));
            if (!$user) {
                return response()->json(["status"=>"Is Not Ok", "msg"=>"Please give valid user"]);
            }

            if (Cache::has("loginToken:{$user->id}")) {
                Cache::forget("loginToken:{$user->id}");
            }

            $user->lastLoginToken = null;
            $user->save();
            
            return response()->json(["status"=>"OK"]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is not OK", "msg"=> $th->getMessage()]);
        }
    }

    public function allUsers(){
        try {
            $data = [];
            if (Cache::has("allUsers")) {
                $data = Cache::get("allUsers");
            } else {
                $user = User::all();
                Cache::put("allUsers", $user, 60*60);
                $data = $user;
            }

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is not OK", "msg"=> $th->getMessage()]);
        }
    }
   
}
