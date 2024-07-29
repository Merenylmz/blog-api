<?php

namespace App\Models;

use App\Models\Scopes\GetBlogStatusTrue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = "blogs";
    protected $fillable = ["title","slug", "description", "userId", "categoryId", "fileUrl", "isitActive", "viewsCount", "comments", "tags", "starterDate", "finishDate"];

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

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->comments)) {
                $model->comments = [];
            }
        });

        static::retrieved(function($model){
            $model->viewsCount += 1;
            $model->save();
        });

        static::addGlobalScope(new GetBlogStatusTrue);
    }
   
}
