<?php

namespace App\Services\Concrete;

use App\Interface\PolicyRepositoryInterface;
use App\Services\Abstract\PolicyServiceInterface;

class PolicyService implements PolicyServiceInterface
{
    private $policyRepository;
    public function __construct(PolicyRepositoryInterface $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function getPoliciesBySlug($slug){
        return $this->policyRepository->getPolicyBySlug($slug);
    }
}
