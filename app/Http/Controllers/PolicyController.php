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

    public function getPolicyBySlug(Request $req, $slug){
        try {
            $policies = [];
            if($slug == "kvkk"){
                $policiesKvkk = Cache::rememberForever("policiesKvkk", function(){
                    return Policy::where("slug","=", "kvkk")->first();
                });
                $policies = $policiesKvkk;
            } else if($slug == "privacy"){
                $policiesPrivacy = Cache::rememberForever("policiesPrivacy", function(){
                    return Policy::where("slug","=", "privacy")->first();
                });
                $policies = $policiesPrivacy;
            } else {
                $policy = Cache::rememberForever("policies", function(){
                    return Policy::all();
                });
                $policies = $policy;
            }
            return response()->json($policies);

            // switch ($slug) {
            //     case 'kvkk':
            //         $policiesKvkk = Cache::rememberForever("policiesKvkk", function(){
            //             return Policy::where("slug","=", "kvkk")->first();
            //         });
            //         return response()->json($policiesKvkk);
                
            //     case 'privacy':
            //         $policiesPrivacy = Cache::rememberForever("policiesPrivacy", function(){
            //             return Policy::where("slug","=", "privacy")->first();
            //         });
            //         return response()->json($policiesPrivacy);  
            //     default:
            //         $policies = Cache::rememberForever("policies", function(){
            //             return Policy::all();
            //         });
            //         return response()->json($policies);
            // }
        } catch (\Throwable $th) {
            return response()->json(["error"=> $th->getMessage()]);
        }
    }
}
