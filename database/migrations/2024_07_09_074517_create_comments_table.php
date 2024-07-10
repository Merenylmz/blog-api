<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userId");
            $table->foreign("userId")->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger("blogId");
            $table->foreign("blogId")->references("id")->on("blogs")->onDelete("cascade");
            $table->text("comment");
            $table->boolean("status")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
