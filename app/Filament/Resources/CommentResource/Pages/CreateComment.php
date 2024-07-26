<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Jobs\NewCommentMailJob;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;

    protected function afterCreate():void{
        $blog = Blog::find($this->record->blogId);
        $comment = Comment::find($this->record->id);
        $blogComments = $blog->comments;
        array_push($blogComments, $comment->id);
        $blog->comments = $blogComments;
        $blog->save();

        NewCommentMailJob::dispatch("m.erenyilmaz2007@gmail.com", [
            "blog"=>$blog,
            "comment"=>$comment
        ]);

    }
}
