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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->unsignedBigInteger("userId");
            $table->foreign("userId")->references("id")->on("users")->onDelete("cascade");
            $table->string("fileUrl")->nullable();
            $table->boolean("isitActive")->default(false);
            $table->unsignedBigInteger("categoryId");
            $table->foreign("categoryId")->references("id")->on("categories")->onDelete("cascade");
            $table->json("comments")->nullable()->default("[]");
            $table->json("tags")->nullable();
            $table->integer("viewsCount")->default(0);
            $table->date("starterDate");
            $table->date("finishDate");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
