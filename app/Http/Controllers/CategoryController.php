<?php

namespace App\Http\Controllers;

use App\Interface\CategoryRepositoryInterface;
use App\Services\Concrete\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }
    //burada ise neredeyse blogla aynı işlemler yapıyoruz
    public function getAllCategories(){
        try {
            $categories = $this->categoryService->all(["status"=>true, "key"=>"allCategory"]);
            return response()->json($categories);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=> $th->getMessage()]);
        }
    }

    public function getCategoryById($id){
        try {
            $category = $this->categoryService->find($id);
            return response()->json($category);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK   ", "msg"=> $th->getMessage()]);
        }
    }
    public function addCategory(Request $req){
        try {
            $status = $this->categoryService->create($req->all());
            return response()->json($status);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=> $th->getMessage()]);
        }
    }
    public function deleteCategory($id){
        try {
            $status = $this->categoryService->delete($id);
            return response()->json($status);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=> $th->getMessage()]);
        }
    }
    public function editCategory(Request $req, $id){
        try {
            $status = $this->categoryService->update($req->all(), $id);
            return response()->json($status);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=> $th->getMessage()]);
        }
    }
}
