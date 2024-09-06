<?php

namespace Jundayw\LaravelSensitive;

use Illuminate\Support\Collection;
use Jundayw\LaravelSensitive\Concerns\SensitiveListenTrait;
use Jundayw\LaravelSensitive\Concerns\SensitiveTrait;
use Jundayw\LaravelSensitive\Contracts\DatabaseInterface;
use Jundayw\LaravelSensitive\Contracts\FilterInterface;
use Jundayw\LaravelSensitive\Contracts\InterceptorInterface;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;

class Sensitive implements SensitiveInterface
{
    use SensitiveTrait;
    use SensitiveListenTrait;

    public function __construct(
        protected ?InterceptorInterface $interceptor = null,
        protected ?FilterInterface      $filter = null
    )
    {
        $this->interceptor = $this->interceptor ?? new LocalInterceptor();
        $this->filter      = $this->filter ?? new Filter();
    }

    public function replacement(string $content, Collection $collection): string
    {
        return str_replace(
            $collection->pluck('stop_words')->toArray(),
            $collection->pluck('replacement')->toArray(),
            $content
        );
    }

    public function filter(string $content, string $field, int $scope = SensitiveInterface::STATUS_ALL): FilterInterface
    {
        $raw        = $content;
        $collection = $this->interceptor->handle($content, $field);

        if ($collection->contains(fn($item) => $item['stop_type'] == DatabaseInterface::VALUE_BLOCK)) {
            $collect = $collection->where('stop_type', DatabaseInterface::VALUE_BLOCK);
            foreach ($this->getListens(SensitiveInterface::STATUS_BLOCK) as $listen) {
                $content = call_user_func($listen, $content, $field, $scope, $collect, $this);
            }
        }

        if ($collection->contains(fn($item) => $item['stop_type'] == DatabaseInterface::VALUE_REVIEW)) {
            $collect = $collection->where('stop_type', DatabaseInterface::VALUE_REVIEW);
            foreach ($this->getListens(SensitiveInterface::STATUS_REVIEW) as $listen) {
                $content = call_user_func($listen, $content, $field, $scope, $collect, $this);
            }
        }

        if ($collection->contains(fn($item) => $item['stop_type'] == DatabaseInterface::VALUE_REPLACE)) {
            $collect = $collection->where('stop_type', DatabaseInterface::VALUE_REPLACE);
            $content = $this->replacement($content, $collect);
            foreach ($this->getListens(SensitiveInterface::STATUS_REPLACE) as $listen) {
                $content = call_user_func($listen, $content, $field, $scope, $collect, $this);
            }
        }

        $collect = $collection->where('stop_type', DatabaseInterface::VALUE_PASS);
        foreach ($this->getListens(SensitiveInterface::STATUS_PASS) as $listen) {
            $content = call_user_func($listen, $content, $field, $scope, $collect, $this);
        }

        return $this->filter->handle($raw, $content, $field, $scope, $collection);
    }

}
