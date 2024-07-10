<?php

namespace App\Services\Concrete;
use App\Interface\CategoryRepositoryInterface;
use App\Services\Abstract\CategoryServiceInterface;

class CategoryService implements CategoryServiceInterface
{
    
    use Common\CommonServiceTrait;

    private $categoryRepository;
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->modelRepository = $this->categoryRepository;
    }
}
