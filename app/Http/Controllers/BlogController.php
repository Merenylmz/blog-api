<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\Concrete\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    protected $blogService;
    public function __construct(BlogService $blogService) {
        $this->blogService = $blogService;
    }
    public function getAllBlogs(Request $req){
        try {
            $blogs = $this->blogService->all(["status"=>true, "key"=>"allBlog"]);
            
            return response()->json(["status"=>"OK", "blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function getBlogById($id){
        try {
            $blog = $this->blogService->find($id);
            return response()->json(["status"=>"OK", $blog]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function editBlog(Request $req, $id){
        try {
            $blog = $this->blogService->find($id);

            if ($req->attributes->get("userId") != $blog->userId) {
                return response()->json(["status"=>"Is Not Ok", "msg"=>"This blogs not edit"]);
            }

            $blogTagsArray = json_decode($blog->tags);
            array_push($blogTagsArray, $req->input("tags"));
            $blog->tags = json_encode($blogTagsArray);

            $newData = [
                "title"=>$req->input("title"),
                "description"=>$req->input("description"),
                "userId"=>$blog->userId,
                "categoryId"=>$req->input("categoryId"),
                "starterDate"=>$req->input("starterDate"),
                "finishDate"=>$req->input("finishDate"),
                "tags"=>$blog->tags,
            ];
            if ($req->hasFile("fileUrl")) {
                $file = $req->file("fileUrl");
                $fileName = "blog"."_".time()."_".$blog->userId."_".$file->getClientOriginalName();
                $file->move(public_path("blogs"), $fileName);
                $fileUrl = url("blog", $fileName);
                array_push($newData, $fileUrl);
            }

            $status = $this->blogService->update($newData, $id);

            return response()->json(["status"=>"OK", $status]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function addViewsCount(Request $req, $id){
        try {
            $newCount = $this->blogService->addViewsCount($id);
            return response()->json(["status"=>"OK", "count"=>$newCount]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function getPopularBlogs(){
        try {
            $blogs = $this->blogService->getPopularBlog();
            return response()->json(["status"=>"OK", "blogs"=>$blogs]);
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }
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
}
