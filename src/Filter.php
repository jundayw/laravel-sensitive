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
    protected int        $scope = 0;
    protected Collection $collection;

    protected array $maps = [
        SensitiveInterface::STATUS_BLOCK   => DatabaseInterface::VALUE_BLOCK,
        SensitiveInterface::STATUS_REVIEW  => DatabaseInterface::VALUE_REVIEW,
        SensitiveInterface::STATUS_REPLACE => DatabaseInterface::VALUE_REPLACE,
        SensitiveInterface::STATUS_PASS    => DatabaseInterface::VALUE_PASS,
    ];

    public function isPass(): bool
    {
        return SensitiveInterface::STATUS_PASS === ($this->scope & SensitiveInterface::STATUS_PASS);
    }

    public function isReplace(): bool
    {
        return SensitiveInterface::STATUS_REPLACE === ($this->scope & SensitiveInterface::STATUS_REPLACE);
    }

    public function isReview(): bool
    {
        return SensitiveInterface::STATUS_REVIEW === ($this->scope & SensitiveInterface::STATUS_REVIEW);
    }

    public function isBlock(): bool
    {
        return SensitiveInterface::STATUS_BLOCK === ($this->scope & SensitiveInterface::STATUS_BLOCK);
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

    public function getValues(int $scope = SensitiveInterface::STATUS_ALL): array
    {
        return array_filter($this->maps, function ($value, $status) use ($scope) {
            return $status === ($this->scope & $scope & $status);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function getStopWords(int $scope = SensitiveInterface::STATUS_ALL): array
    {
        return $this->collection->whereIn('stop_type', $this->getValues($scope))->pluck('stop_words')->toArray();
    }

}
