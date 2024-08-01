<?php

namespace App\Repository;
use App\Interface\PolicyRepositoryInterface;
use App\Models\Policy;
use Illuminate\Support\Facades\Cache;

class PolicyRepository implements PolicyRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    private $policy;
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }

    // $policies = Cache::rememberForever("policies".$slug, fn()=> $slug == "all" ? Policy::all() : Policy::where("slug","=", $slug)->first()); 

    public function getPolicyBySlug($slug){
        return  Cache::rememberForever("policies".$slug, fn()=> $slug == "all" ? $this->policy->all() : $this->policy->where("slug","=", $slug)->first());
    }
}
