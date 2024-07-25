<?php

namespace App\Console\Commands;

use App\Mail\NewCommentMail;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BlogChangeStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:blog-change-status-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $currentTime = Carbon::now();
        Blog::where("starterDate","<", $currentTime)->update(["isitActive"=>1]);
        Blog::where("finishDate", "<", $currentTime)->update(["isitActive"=>0]);
    }
}
