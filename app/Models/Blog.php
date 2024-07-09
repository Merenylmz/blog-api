<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ["title", "description", "userId", "categories", "fileUrl", "isitActive", "viewsCount", "comments", "tags"];

    public function categories(){
        return $this->hasMany(Category::class);
    }
}
