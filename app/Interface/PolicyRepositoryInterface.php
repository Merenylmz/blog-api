<?php

namespace App\Interface;

interface PolicyRepositoryInterface
{
    public function getPolicyBySlug($slug);
}
