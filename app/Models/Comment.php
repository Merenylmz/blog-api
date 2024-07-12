<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["userId", "blogId", "comment", "status"];

    public function blog(){
        return $this->belongsTo(Blog::class);
    }
}
