<?php

namespace App\Services\Concrete;

use App\Interface\BlogRepositoryInterface;
use App\Services\Abstract\BlogServiceInterface;
use Illuminate\Http\Request;

class BlogService implements BlogServiceInterface
{
    
    use Common\CommonServiceTrait;

    protected $blogRepository;
    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->modelRepository = $this->blogRepository;
    }

    public function addViewsCount($id){
        try {
            $blog = $this->blogRepository->addViewsCount($id);

            return $blog;
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function getPopularBlog(){
        try {
            $blogs = $this->blogRepository->getPopularBlog();

            return $blogs;
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    public function addComments($data, $id){
        try {
            $blogs = $this->blogRepository->addComment($data, $id);
            
            return $blogs;
        } catch (\Throwable $th) {
            return response()->json(["status"=>"Is Not OK", "msg"=>$th->getMessage()]);
        }
    }

    
}
