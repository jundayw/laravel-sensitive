<?php

namespace Jundayw\LaravelSensitive;

use Illuminate\Support\Collection;
use Jundayw\LaravelSensitive\Concerns\FilterEventTrait;
use Jundayw\LaravelSensitive\Concerns\FilterTrait;
use Jundayw\LaravelSensitive\Contracts\DatabaseInterface;
use Jundayw\LaravelSensitive\Contracts\FilterInterface;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;

class Filter implements FilterInterface
{
    use FilterTrait;
    use FilterEventTrait;

    protected string     $raw;
    protected string     $content;
    protected string     $field;
    protected int        $scope;
    protected Collection $collection;

    public function isPass(): bool
    {
        return $this->collection->contains(function ($item) {
            return $item['stop_type'] == DatabaseInterface::VALUE_PASS;
        });
    }

    public function isReplace(): bool
    {
        return $this->collection->contains(function ($item) {
            return $item['stop_type'] == DatabaseInterface::VALUE_REPLACE;
        });
    }

    public function isReview(): bool
    {
        return $this->collection->contains(function ($item) {
            return $item['stop_type'] == DatabaseInterface::VALUE_REVIEW;
        });
    }

    public function isBlock(): bool
    {
        return $this->collection->contains(function ($item) {
            return $item['stop_type'] == DatabaseInterface::VALUE_BLOCK;
        });
    }

    public function handle(string $raw, string $content, string $field, int $scope = SensitiveInterface::STATUS_ALL, Collection $collection): static
    {
        $this->raw        = $raw;
        $this->content    = $content;
        $this->field      = $field;
        $this->scope      = $scope;
        $this->collection = $collection;

        return $this;
    }

}
