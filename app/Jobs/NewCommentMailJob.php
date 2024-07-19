<?php

namespace App\Jobs;

use App\Mail\NewCommentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewCommentMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $email, $infos; // email ve info bilgilerini burada global değişken olarak tanımlıyoruz.
    public function __construct($email, $infos)
    {
        $this->email = $email;
        $this->infos = $infos;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //burada ise maili yolluyoruz
        Mail::to($this->email)->send(new NewCommentMail($this->infos));
    }
}
