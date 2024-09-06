<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Illuminate\Support\Collection;

trait FilterTrait
{
    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getScope(): int
    {
        return $this->scope;
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

}
