<?php

namespace Jundayw\LaravelSensitive\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Jundayw\LaravelSensitive\Contracts\EventInterface;

class SensitivePassListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event): void
    {
        // $event->getPayload();
    }

}
