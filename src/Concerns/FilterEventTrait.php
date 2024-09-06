<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Illuminate\Support\Facades\Event;
use Jundayw\LaravelSensitive\Contracts\DatabaseInterface;
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
        $isPass    = (SensitiveInterface::STATUS_PASS & $scope) === SensitiveInterface::STATUS_PASS;
        $isReplace = (SensitiveInterface::STATUS_REPLACE & $scope) === SensitiveInterface::STATUS_REPLACE;
        $isReview  = (SensitiveInterface::STATUS_REVIEW & $scope) === SensitiveInterface::STATUS_REVIEW;
        $isBlock   = (SensitiveInterface::STATUS_BLOCK & $scope) === SensitiveInterface::STATUS_BLOCK;

        if ($scope === SensitiveInterface::STATUS_ALL || $isPass) {
            $this->events[SensitiveInterface::STATUS_PASS][] = $event;
        }

        if ($scope === SensitiveInterface::STATUS_ALL || $isReplace) {
            $this->events[SensitiveInterface::STATUS_REPLACE][] = $event;
        }

        if ($scope === SensitiveInterface::STATUS_ALL || $isReview) {
            $this->events[SensitiveInterface::STATUS_REVIEW][] = $event;
        }

        if ($scope === SensitiveInterface::STATUS_ALL || $isBlock) {
            $this->events[SensitiveInterface::STATUS_BLOCK][] = $event;
        }

        return $this;
    }

    public function dispatch(...$arguments): void
    {
        $maps = [
            DatabaseInterface::VALUE_BLOCK   => SensitiveInterface::STATUS_BLOCK,
            DatabaseInterface::VALUE_REVIEW  => SensitiveInterface::STATUS_REVIEW,
            DatabaseInterface::VALUE_REPLACE => SensitiveInterface::STATUS_REPLACE,
            DatabaseInterface::VALUE_PASS    => SensitiveInterface::STATUS_PASS,
        ];
        foreach ($maps as $value => $event) {
            if (false === $this->collection->contains(fn($item) => $item['stop_type'] == $value)) {
                continue;
            }
            array_map(function ($event) use ($arguments) {
                Event::dispatch(new $event(...$arguments));
            }, $this->getEvents($event));
        }
    }

}
