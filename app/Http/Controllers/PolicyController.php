<?php

namespace App\Http\Controllers;

use App\Models\Kvkk;
use App\Models\Policy;
use App\Models\PrivacyPolicy;
use App\Services\Concrete\PolicyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PolicyController extends Controller
{
    //Burada ise Cache ile Kvkk ve Privacy Policy belgelerini getiriyoruz Sürekli güncellenen bir kısım olmadığı için Cache'i silmek mantıksız olur.

    private $policyService;
    public function __construct(PolicyService $policyService) {
        $this->policyService = $policyService;
    }
    public function getPolicyBySlug(Request $req, $slug){
        try {
            $policies = $this->policyService->getPoliciesBySlug($slug);
            return response()->json($policies);
        } catch (\Throwable $th) {
            return response()->json(["error"=> $th->getMessage()], 500);
        }
    }
}
