<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Illuminate\Support\Facades\Event;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;
use Jundayw\LaravelSensitive\Events\SensitiveBlockEvent;
use Jundayw\LaravelSensitive\Events\SensitivePassEvent;
use Jundayw\LaravelSensitive\Events\SensitiveReplaceEvent;
use Jundayw\LaravelSensitive\Events\SensitiveReviewEvent;

trait FilterEventTrait
{
    protected array $events = [
        SensitiveInterface::STATUS_PASS    => [
            SensitivePassEvent::class,
        ],
        SensitiveInterface::STATUS_REPLACE => [
            SensitiveReplaceEvent::class,
        ],
        SensitiveInterface::STATUS_REVIEW  => [
            SensitiveReviewEvent::class,
        ],
        SensitiveInterface::STATUS_BLOCK   => [
            SensitiveBlockEvent::class,
        ],
    ];

    public function getEvents(?int $events = null): array
    {
        if (is_null($events)) {
            return $this->events;
        }
        if (array_key_exists($events, $this->events)) {
            return $this->events[$events];
        }
        return [];
    }

    public function event(int $scope, string $event): static
    {
        foreach ($this->getEvents() as $status => $events) {
            if ($status === ($scope & $status)) {
                $this->events[$status][] = $event;
            }
        }

        return $this;
    }

    public function dispatch(...$arguments): void
    {
        foreach ($this->getEvents() as $status => $events) {
            if ($status === ($this->scope & $status)) {
                array_map(function ($event) use ($arguments) {
                    Event::dispatch(new $event(...$arguments));
                }, $this->getEvents($status));
            }
        }
    }

}
