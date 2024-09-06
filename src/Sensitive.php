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

    protected int $scope = 0;

    public function __construct(
        protected ?InterceptorInterface $interceptor = null,
        protected ?FilterInterface      $filter = null
    )
    {
        $this->interceptor = $this->interceptor ?? new DatabaseInterceptor();
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

        $maps = [
            DatabaseInterface::VALUE_BLOCK   => SensitiveInterface::STATUS_BLOCK,
            DatabaseInterface::VALUE_REVIEW  => SensitiveInterface::STATUS_REVIEW,
            DatabaseInterface::VALUE_PASS    => SensitiveInterface::STATUS_PASS,
            DatabaseInterface::VALUE_REPLACE => SensitiveInterface::STATUS_REPLACE,
        ];

        foreach ($maps as $value => $status) {
            if (($collect = $collection->where('stop_type', $value))->isNotEmpty()) {
                $this->scope |= $status;
                if (SensitiveInterface::STATUS_REPLACE == $status) {
                    $content = $this->replacement($content, $collect);
                }
                foreach ($this->getListens($status) as $listen) {
                    $content = call_user_func($listen, $content, $field, $this->scope, $collect, $this);
                }
            }
        }

        return $this->filter->handle($raw, $content, $field, $this->scope, $collection);
    }

}
