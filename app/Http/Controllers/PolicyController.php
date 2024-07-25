<?php

namespace App\Http\Controllers;

use App\Models\Kvkk;
use App\Models\Policy;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PolicyController extends Controller
{
    //Burada ise Cache ile Kvkk ve Privacy Policy belgelerini getiriyoruz Sürekli güncellenen bir kısım olmadığı için Cache'i silmek mantıksız olur.
    // public function getKvkkDocument(){
    //     try {
    //         $kvkk = [];
    //         if (Cache::has("kvkkDoc")) {
    //             $kvkk = Cache::get("kvkkDoc");
    //         } else {
    //             $kvkk = Kvkk::all();
    //             Cache::put("kvkkDoc", $kvkk, 60*120);
    //         }
    //         return response()->json($kvkk[0]);
    //     } catch (\Throwable $th) {
    //         return response()->json(["error"=> $th->getMessage()]);
    //     }
    // }
    // public function getPrivacyDocument(){
    //     try {
    //         $privacyPolicy = [];
    //         if (Cache::has("privacyPolicyDoc")) {
    //             $privacyPolicy = Cache::get("privacyPolicyDoc");
    //         } else {
    //             $privacyPolicy = PrivacyPolicy::all();
    //             Cache::put("privacyPolicyDoc", $privacyPolicy, 60*120);
    //         }
    //         return response()->json($privacyPolicy[0]);
    //     } catch (\Throwable $th) {
    //         return response()->json(["error"=> $th->getMessage()]);
    //     }
    // }


    public function getPolicyBySlug(Request $req){
        try {
            switch ($req->query("policy")) {
                case 'kvkk':
                    $policiesKvkk = Cache::rememberForever("policiesKvkk", function(){
                        return Policy::where("slug","=", "kvkk")->first();
                    });
                    return response()->json($policiesKvkk);
                
                case 'privacy':
                    $policiesPrivacy = Cache::rememberForever("policiesPrivacy", function(){
                        return Policy::where("slug","=", "privacy")->first();
                    });
                    return response()->json($policiesPrivacy);  
                default:
                    $policies = Cache::rememberForever("policies", function(){
                        return Policy::all();
                    });
                    return response()->json($policies);
            }
        } catch (\Throwable $th) {
            return response()->json(["error"=> $th->getMessage()]);
        }
    }
}
