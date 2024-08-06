<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /*
    Bu Kısımda Login İşlemi Yapıyoruz kullanıcının emailini kontrol edip sistemde varmı ona bakıyoruz sonrasında Hash::check ile sistemdeki
    şifre ile karşılaştırıyoruz bize eşitse true dönüyor. sonrasında token üretip Cache ile redise ve database' e kaydediyoruz her kullanıcı 
    için farklı bir Cache kaydediyor
    yani dinamik bir sistem sonra kullanıcının profil fotoğrafı varsa onuda bize döndürüyor.
    */

    use ResponseTrait;

    public function login(Request $req){
        try {
            $info = $req->only("email", "password");
            if (!$token = JWTAuth::attempt($info)) {
                return $this->errorResponse("Unauthorized");
            }
            $user = Auth::user();

            User::where("id", $user->id)->update(["last_login_token"=>$token]);
            Cache::put("loginToken:{$user->id}", $token, 60*60);

            return (new LoginResource($user))->additional(["token"=>$token]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"OK", "msg"=> $th->getMessage()], 500);
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

            return $this->successResponse($newUser->name);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function logout(Request $req){
        try {
            $user = User::find($req->userId);
            Cache::has("loginToken:{$user->id}") ?? Cache::forget("loginToken:{$user->id}");

            $user->last_login_token = null;
            $user->save();

            JWTAuth::invalidate($req->query("token"));
            
            return $this->successResponse("Logouted");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //Burada Frontta belirli bir filtreleme işlemi yapmak için Tüm kullanıcıları döndürüyoruz sistemde yavaşlama olmasın diye Cache sistemi kullanıyoruz.
    public function allUsers(){
        try {
            $users = Cache::rememberForever("allUsers", function(){
                return User::all();
            });
            return $this->successResponse($users);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
   
}
