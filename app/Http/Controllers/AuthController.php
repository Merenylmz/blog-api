<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Jerry\JWT\JWT;

class AuthController extends Controller
{
    /*
    Bu Kısımda Login İşlemi Yapıyoruz kullanıcının emailini kontrol edip sistemde varmı ona bakıyoruz sonrasında Hash::check ile sistemdeki
    şifre ile karşılaştırıyoruz bize eşitse true dönüyor. sonrasında token üretip Cache ile redise ve database' e kaydediyoruz her kullanıcı 
    için farklı bir Cache kaydediyor
    yani dinamik bir sistem sonra kullanıcının profil fotoğrafı varsa onuda bize döndürüyor.
    */
    public function login(Request $req){
        try {
            $user = User::where("email", $req->input("email"))->first();
            if (!$user) {return response()->json(["status"=>"Is Not OK", "msg"=> "User is not found"]);}
            if (!Hash::check($req->input("password"), $user->password)) {
                return response()->json(["status"=>"Is Not OK", "msg"=> "Wrong password"]); 
            } 

            $token = JWT::encode(["userId"=>$user->id, "isitAdmin"=>$user->isitAdmin]);
            Cache::put("loginToken:{$user->id}", $token, 60*60);
            $user->last_login_token = $token;
            $user->save();


            $response = ["status"=>"OK", "token"=>$token];
            if ($user->avatar_url != null) {
                $avatarUrl = Storage::url($user->avatar_url);
                array_push($response, url($avatarUrl));
            }
            return (new LoginResource($user))->additional(["token"=>$token]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()]);
        }
    }


    /*
        Burda Kullanıcıyı Sisteme Kaydediyoruz API ile ve yeni kullanıcı eklediğinde Cache ile bütün userları redisten silip  tekrardan güncel halini yüklüyoruz.
        ve Hash kullanarak şifreyi Hashliyoruz
    */ 
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

            Cache::has("allUsers") ?? Cache::forget("allUsers");

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

    

    /*
    Bu kısımda sisteme giriş yapmış kullanıcıyı çıkarma işlemini yapıyoruz buna bağlı bulunan middleware ile kullanıcının sistemde oturum açıp açmadığına bakıyoruz.
    sonrasında eğer controllera gelirse kullanıcının rediste bulunan ve databasede bulunan token siliniyor. 
     */
    public function logout(Request $req){
        try {
            $user = User::find($req->attributes->get("userId"));
            if (!$user) {
                return response()->json(["status"=>"Is Not Ok", "msg"=>"Please give valid user"]);
            }
            
            Cache::has("loginToken:{$user->id}") ?? Cache::forget("loginToken:{$user->id}");

            $user->last_login_token = null;
            $user->save();
            
            return response()->json(["status"=>"OK"]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is not OK", "msg"=> $th->getMessage()]);
        }
    }


    //Burada Frontta belirli bir filtreleme işlemi yapmak için Tüm kullanıcıları döndürüyoruz sistemde yavaşlama olmasın diye Cache sistemi kullanıyoruz.
    public function allUsers(){
        try {
            $users = Cache::rememberForever("allUsers", function(){
                return User::all();
            });
            return response()->json($users);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is not OK", "msg"=> $th->getMessage()]);
        }
    }
   
}
