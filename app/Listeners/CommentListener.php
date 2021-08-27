<?php

namespace App\Listeners;

use App\Events\CommentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentEvent  $event
     * @return void
     */
    public function handle(CommentEvent $event)
    {
        //
    }
}
