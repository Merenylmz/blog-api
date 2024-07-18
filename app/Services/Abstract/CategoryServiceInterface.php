<?php

namespace App\Services\Abstract;
use App\Services\Abstract\Common\CommonServiceInterface;

interface CategoryServiceInterface extends CommonServiceInterface
{
    public function getAllCategoriesWithCache();
}
