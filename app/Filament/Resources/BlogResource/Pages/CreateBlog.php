<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function afterCreate(): void{

        if (Cache::has("allBlog")) {
            Cache::forget("allBlog");
        }
        $category = Category::find($this->record->categoryId);
        $categoryBlogsArray = $category->blogs;
        array_push($categoryBlogsArray, $this->record->id);
        $category->blogs = $categoryBlogsArray;
        $category->save();
    }
}
