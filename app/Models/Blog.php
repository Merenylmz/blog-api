<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = "blogs";
    protected $fillable = ["title", "description", "userId", "categoryId", "fileUrl", "isitActive", "viewsCount", "comments", "tags", "starterDate", "finishDate"];

    protected $casts = [
        'tags' => 'array',
        'categories'=> 'array',
        'comments'=>'array'
    ];

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

   
}
