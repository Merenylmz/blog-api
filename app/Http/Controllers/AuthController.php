<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller implements HasMiddleware
{
    use ResponseTrait;

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth.api', except: ['login', 'register', 'allUsers']),
        ];
    }

    public function login(Request $req){
        try {
            $info = $req->only("email", "password");
            $token = Auth::guard("api")->attempt($info);
            if (!$token) {
                return $this->errorResponse("Unauthorized");
            }
            $user = Auth::guard("api")->user();

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

            // $creditenatls = $req->only("email", "password");
            // $token = Auth::login($creditenatls);

            // dd($token);

            return $this->successResponse($newUser->name);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function logout(Request $req){
        try {
            Auth::guard("api")->logout();
            
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
