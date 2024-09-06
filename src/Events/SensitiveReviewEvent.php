<?php

namespace Jundayw\LaravelSensitive\Events;

use Jundayw\LaravelSensitive\Contracts\EventInterface;

class SensitiveReviewEvent implements EventInterface
{
    protected array $payload = [];

    public function __construct(...$payload)
    {
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

}
