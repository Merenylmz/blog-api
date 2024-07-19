<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Jerry\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;

class IsItAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        //Burada ise JWT ile gelen tokenı decode ediyoruz ve token içinde bulunan userId bilgisine bağlı olarak Userı buluyoruz. ve adminmi ona bakıyoruz
        $decodedToken = JWT::decode($req->query("token"));
        $user = User::find($decodedToken["userId"]);
        if (!$user) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"User is not found"]);
        }
        else if (!$user->isitAdmin) {
            return response()->json(["status"=>"Is Not OK", "msg"=>"Unauthorized Entry"]);
        }
        
        return $next($req);
    }
}
