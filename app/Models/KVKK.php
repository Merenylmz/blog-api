<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kvkk extends Model
{
    use HasFactory;

    protected $table = "kvkkpage";
    protected $fillable = ["title", "description"];

}
