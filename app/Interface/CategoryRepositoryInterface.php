<?php

namespace App\Interface;
use App\Interface\Common\CommonRepositoryInterface;

interface CategoryRepositoryInterface extends CommonRepositoryInterface
{
    public function getAllCategoriesWithCache();
}
