<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\Concrete\BlogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    use ResponseTrait;
    protected $blogService; // Blog Servicei burda global değişken olabilmesi için constructor ile veriyi atıyoruz ve global alanda kullanıyoruz.
    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService; // Burada veriyi aktarıyoruz
    }

    /*
        Bütün Blogları Service ile getirip Listeliyoruz burada all metodunun aldığı dizideki status ve key bilgileri 
        Cache sistemini ayarlıyor arka plandaki status=> true ise Cache sistemi aktif olur ve Key=> bilgisi redise nasıl kayıt olucağıdır
     */
    public function getAllBlogs(Request $req){
        try {
            $blogs = $this->blogService->all(["status"=>true, "key"=>"allBlog"]);
            
            return $this->successResponse(["blogs"=> $blogs]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //Burada service ile blogları idye göre çekiyoruz.
    public function getBlogBySlug($slug){
        try {
            $blog = $this->blogService->findBySlug($slug);
            return $this->successResponse($blog);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //Edit Blog Silindi    

    //burada Blogdaki görüntülenme sayısını artırıyoruz...
    public function addViewsCount(Request $req, $id){
        try {
            $newCount = $this->blogService->addViewsCount($id);
            return $this->successResponse(["count"=>$newCount]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //Burada popular olan blogları getiriyoruz...
    public function getPopularBlogs(){
        try {
            $blogs = $this->blogService->getPopularBlog();
            return $this->successResponse(["blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
    //Burada blogumuza yorum ekleyebilmek için bir API yazdık yorumları getirirken bir Cache ile getiriyorduk ve burada Commentde bir güncelleme olduğu için siliyoruz.
    public function addComments(Request $req, $id){
        try {
            $blogs = $this->blogService->addComments($req->all(), $id);
            if (Cache::has("allComment")) {
                Cache::forget("allComment");
            }

            return $this->successResponse($blogs);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //Burada ise sadece aktif olan yorumları getiriyor.
    public function getAllComments(){
        try {
            $comments = [];
            if (Cache::has("allComment")) {
                $comments = Cache::get("allComment");
            } else {
                $comments = Comment::where("status", true)->get();
                Cache::put("allComment", $comments,60*15);
            }
 
            return $this->successResponse(["comments"=>$comments]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    //burada ise CategoryId ye bağlı olarak sadece aktif olan blogları getiriyor.
    public function getBlogByCategoryId(Request $req){
        try {
            $idsOrSlugs = explode(',', $req->query("category"));
            $blogs = $this->blogService->getBlogByCategoryId($idsOrSlugs);
            return $this->successResponse(["blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
