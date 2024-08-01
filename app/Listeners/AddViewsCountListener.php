<?php

namespace App\Listeners;

use App\Events\AddViewsCountEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddViewsCountListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AddViewsCountEvents $event): void
    {
        $event->model->viewsCount += 1;
        $event->model->save();
    }
}
