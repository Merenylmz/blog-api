<?php

namespace App\Repository;
use App\Interface\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    
    use Common\CommonRepositoryTrait;

    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->model = $this->category;
    }

    public function getAllCategoriesWithCache(){
        
    }
}
