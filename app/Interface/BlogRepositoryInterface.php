<?php

namespace App\Interface;

use App\Interface\Common\CommonRepositoryInterface;
use Illuminate\Http\Request;

interface BlogRepositoryInterface extends CommonRepositoryInterface
{
    public function getPopularBlog();

    public function addComment($data, $id);

    public function addViewsCount($id);
}
