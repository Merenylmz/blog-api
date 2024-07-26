<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\Concrete\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
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
            
            return response()->json(["status"=>"OK", "blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    //Burada service ile blogları idye göre çekiyoruz.
    public function getBlogById($id){
        try {
            $blog = $this->blogService->find($id);
            return response()->json(["status"=>"OK", $blog]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    //Edit Blog Silindi    

    //burada Blogdaki görüntülenme sayısını artırıyoruz...
    public function addViewsCount(Request $req, $id){
        try {
            $newCount = $this->blogService->addViewsCount($id);
            return response()->json(["status"=>"OK", "count"=>$newCount]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    //Burada popular olan blogları getiriyoruz...
    public function getPopularBlogs(){
        try {
            $blogs = $this->blogService->getPopularBlog();
            return response()->json(["status"=>"OK", "blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }
    //Burada blogumuza yorum ekleyebilmek için bir API yazdık yorumları getirirken bir Cache ile getiriyorduk ve burada Commentde bir güncelleme olduğu için siliyoruz.
    public function addComments(Request $req, $id){
        try {
            $blogs = $this->blogService->addComments($req->all(), $id);
            if (Cache::has("allComment")) {
                Cache::forget("allComment");
            }

            return response()->json(["status"=>"OK", $blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
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
 
            return response()->json(["status"=>"OK", "comments"=>$comments]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    //burada ise CategoryId ye bağlı olarak sadece aktif olan blogları getiriyor.
    public function getBlogByCategoryId(Request $req){
        try {
            $blogs = $this->blogService->getBlogByCategoryId($req->input("categories"));
            return response()->json(["status"=>"OK", "blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }
}
