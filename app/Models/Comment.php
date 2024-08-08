<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["userId", "blogId", "comment", "status"];

    public function blog(){
        return $this->belongsTo(Blog::class);
    }

    protected static function booted(){
        parent::booted();
            
        static::creating(function($model){
            Cache::has("allComment") &&  Cache::forget("allComment");
        });
        static::updating(function(){
            Cache::has("allComment") &&  Cache::forget("allComment");
        });
    }
}
