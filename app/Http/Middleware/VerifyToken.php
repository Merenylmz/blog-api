<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        //Burada ise Token bilgisinden gelen userId ile userı buluyoruz. ve token bilgisi geçmişmi ve geçerlimi onu kontrol ediyoruz
        $userId = JWTAuth::setToken($req->query("token"))->getPayload()->get("sub");
        $user = Auth::user();
        // $decodedToken = JWT::decode($req->query("token"));
        if (!$user) {  
            return $this->errorResponse("User is Not found");
        }
        else if ($user->id != $userId) {
            return $this->errorResponse("Invalid Token Try Again");
        }
        else if (!Cache::has("loginToken:{$userId}")) {
            return $this->errorResponse("Token Expired");
        } 
        //burada Databasedeki token bilgisi ile verilen token bilgisi eşleşiyormu ve cachedeki token bilgisi ile verilen token bilgisi eşleşiyormu ona bakıyoruz.  
        else if (Cache::get("loginToken:{$userId}") != $req->query("token") || $req->query("token") != $user->last_login_token) { 
            return $this->errorResponse("Token is Invalid");
        }
        
        // $req->attributes->set("userId", $user->id); // burada ise gelen tokena bağlı olarak userId yi decode edip controllera atıyorum
        $req->merge(["userId"=>$userId]);
        return $next($req);
    }
}
