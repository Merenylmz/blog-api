<?php

namespace App\Repository;
use App\Interface\BlogRepositoryInterface;
use App\Jobs\NewCommentMailJob;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogRepository implements BlogRepositoryInterface
{
    
    use Common\CommonRepositoryTrait;

    protected $blog;
    public function __construct(Blog $blog)
    {
        $this->blog = $blog;
        $this->model = $this->blog;
    }

    public function getPopularBlog(){
        return $this->blog->orderBy("viewsCount", "desc")->where("isitActive", true)->get();
    }

    public function addComment($data, $id){
        $newComment = new Comment();
        $newComment->blogId = $id;
        $newComment->comment = $data["comment"];
        $newComment->userId = $data["userId"];
        $newComment->save();


        $blog = Blog::find($id);
        // $commentArray = json_decode($blog->comments, true);
        // array_push($commentArray, $newComment->id);
        // $blog->comments = json_encode($commentArray);
        // $blog->save();

        // $commentArray = $blog->comments;
        // array_push($commentArray, $newComment->id);
        // $blog->comments = $commentArray;
        // $blog->save();

        NewCommentMailJob::dispatch("m.erenyilmaz2007@gmail.com", [
            "blog"=>$blog,
            "comment"=>$newComment
        ]);

        return ["Blog"=>$blog, "comment"=>$newComment];
    }

    public function addViewsCount($id){
        $blogs = $this->blog->findOrFail($id);
        $blogs->viewsCount += 1;
        $blogs->save();
        return $blogs;
    }

    public function getBlogByCategoryId($id){
        $blogs = [];
        if (!is_numeric($id[0])) {
            $categoryIds = Category::whereIn("slug", $id)->pluck('id');
            $blogs = $this->blog->whereIn("categoryId", $categoryIds)->where("isitActive", true)->get();
        } else {
            $blogs = $this->blog->whereIn("categoryId", $id)->where("isitActive", true)->get();
        }
        return $blogs;
    }
}
