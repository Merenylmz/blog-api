<?php

use App\Http\Middleware\IsItAdmin;
use App\Http\Middleware\VerifyToken;
use App\Mail\NewCommentMail;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Mail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            "verifyToken" => VerifyToken::class,
            "isItAdmin"=>IsItAdmin::class
        ]);
    })
    ->withSchedule(function(Schedule $schedule){
        // $schedule->call(function(){
        //     Mail::to("m.erenyilmaz2007@gmail.com")->send(new NewCommentMail("asdasdasd"));
        // })->everyFiveSeconds();
        // $schedule->call(function(){
        //     $currentTime = Carbon::now();
        //     Blog::where("starterDate","<", $currentTime)->update(["isitActive"=>1]);
        //     Blog::where("finishDate", "<", $currentTime)->update(["isitActive"=>0]);
        // })->daily();

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    