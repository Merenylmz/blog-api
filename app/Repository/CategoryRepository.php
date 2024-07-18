<?php

namespace App\Repository;
use App\Interface\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

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
        $categories = [];
        if (Cache::has("allCat")) {
            $categories = Cache::get("allCat");    
        } else {
            $categories = $this->category->where("status", true)->get();
            Cache::put("allCat", $categories, 60*60);
        }
        return $categories;
    }
}
