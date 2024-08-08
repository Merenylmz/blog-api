<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = ["title", "status", "blogs", "slug"];

    protected $casts = [
        "blogs"=>'array'
    ];

    public function blogs(){
        return $this->hasMany(Blog::class);
    }
    protected static function booted()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->blogs)) {
                $model->blogs = [];
            }
            Cache::has("allCategory") && Cache::forget("allCategory");
        });
        static::deleting(function(){
            Cache::has("allCategory") && Cache::forget("allCategory");
        });
    }
}
