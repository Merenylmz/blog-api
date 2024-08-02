<?php

namespace App\Http\Controllers;

use App\Interface\CategoryRepositoryInterface;
use App\Services\Concrete\CategoryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseTrait;
    private $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }
    //burada ise neredeyse blogla aynı işlemler yapıyoruz
    public function getAllCategories(){
        try {
            $categories = $this->categoryService->all(["status"=>true,"key"=>"allCategory"]);
            return $this->successResponse($categories);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function getCategoryBySlug($slug){
        try {
            $category = $this->categoryService->findBySlug($slug);
            return $this->successResponse($category);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function addCategory(Request $req){
        try {
            $status = $this->categoryService->create($req->all());
            return $this->successResponse($status);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function deleteCategory($id){
        try {
            $status = $this->categoryService->delete($id);
            return $this->successResponse($status);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    public function editCategory(Request $req, $id){
        try {
            $status = $this->categoryService->update($req->all(), $id);
            return $this->successResponse($status);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
