<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function afterCreate(): void{

        $category = Category::find($this->record->categoryId);
        $categoryBlogsArray = json_decode($category->blogs);
        array_push($categoryBlogsArray, $this->record->id);
        $category->blogs = json_encode($categoryBlogsArray);
        $category->save();
    }
}
