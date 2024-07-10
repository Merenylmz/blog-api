<?php

namespace App\Actions\Blog;

class CreateBlogAction
{
    public static function execute(){
        
        return redirect()->route("/admin");
    }
}
