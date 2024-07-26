<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jerry\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        //Burada ise Token bilgisinden gelen userId ile userı buluyoruz. ve token bilgisi geçmişmi ve geçerlimi onu kontrol ediyoruz
        $decodedToken = JWT::decode($req->query("token"));
        $user = User::find($decodedToken["userId"]);
        if (!$user) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"User is not found"]);
        }
        else if (!Cache::has("loginToken:{$user->id}")) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"Token is Expired"]);
        } 
        //burada Databasedeki token bilgisi ile verilen token bilgisi eşleşiyormu ve Cachedeki token bilgisi ile verilen token bilgisi eşleşiyormu ona bakıyoruz.  
        else if (Cache::get("loginToken:{$user->id}") != $req->query("token") && $req->query("loginToken:{$user->id}") != $user->last_login_token) { 
            return response()->json(["status"=>"Is Not OK", "msg"=>"Token is Invalid"]);
        }
        $req->attributes->set("userId", $user->id); // burada ise gelen tokena bağlı olarak userId yi decode edip controllera atıyorum
        return $next($req);
    }
}
