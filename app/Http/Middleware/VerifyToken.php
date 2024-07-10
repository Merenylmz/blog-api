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
        $decodedToken = JWT::decode($req->query("token"));
        $user = User::find($decodedToken["userId"]);
        if (!$user) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"User is not found"]);
        }
        else if (!Cache::has("loginToken:{$user->id}")) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"Token is Expired"]);
        } 
        else if (Cache::get("loginToken:{$user->id}") != $req->query("token") || $req->query("loginToken:{$user->id}") != $user->lastLoginToken) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"Token is Invalid"]);
        }
        $req->attributes->set("userId", $user->id);
        return $next($req);
    }
}
