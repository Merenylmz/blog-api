<?php

namespace App\Http\Controllers;

use App\Models\Kvkk;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function getKvkkDocument(){
        try {
            $kvkk = Kvkk::all();

            return response()->json($kvkk[0]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function getPrivacyDocument(){
        try {
            $privacyPolicy = PrivacyPolicy::all();

            return response()->json($privacyPolicy[0]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
