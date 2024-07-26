<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use App\Jobs\NewCommentMailJob;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        
        $admins = DB::table("model_has_roles")->where("role_id", 1)->get();
        foreach($admins as $admin){
            $user = User::find($admin->model_id);
            NewCommentMailJob::dispatch($user->email, [
                "blog"=>$blog,
                "comment"=>$comment
            ]);
        }
    }
}
