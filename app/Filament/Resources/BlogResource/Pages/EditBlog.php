<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Models\Blog;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after(function(Blog $blog){
                Storage::disk("public")->delete($blog->fileUrl);
            }),
        ];
    }

    protected function afterFill(): void
    {
        $blog = Blog::find($this->record->id);
        Storage::disk("public")->delete($blog->fileUrl);
        $blog->save();
    }
}
