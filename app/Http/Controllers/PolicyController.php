<?php

namespace App\Http\Controllers;

use App\Models\Kvkk;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PolicyController extends Controller
{
    public function getKvkkDocument(){
        try {
            $kvkk = [];
            if (Cache::has("kvkkDoc")) {
                $kvkk = Cache::get("kvkkDoc");
            } else {
                $kvkk = Kvkk::all();
                Cache::put("kvkkDoc", $kvkk, 60*120);
            }
            return response()->json($kvkk[0]);
        } catch (\Throwable $th) {
            return response()->json(["error"=> $th->getMessage()]);
        }
    }
    public function getPrivacyDocument(){
        try {
            $privacyPolicy = [];
            if (Cache::has("privacyPolicyDoc")) {
                $privacyPolicy = Cache::get("privacyPolicyDoc");
            } else {
                $privacyPolicy = PrivacyPolicy::all();
                Cache::put("privacyPolicyDoc", $privacyPolicy, 60*120);
            }
            return response()->json($privacyPolicy[0]);
        } catch (\Throwable $th) {
            return response()->json(["error"=> $th->getMessage()]);
        }
    }
}
