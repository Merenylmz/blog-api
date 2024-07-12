<?php

namespace App\Services\Abstract;
use App\Services\Abstract\Common\CommonServiceInterface;
use Illuminate\Http\Request;

interface BlogServiceInterface extends CommonServiceInterface
{
    public function addViewsCount($id);
    public function addComments($data, $id);
}
