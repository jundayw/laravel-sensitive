<?php

namespace Jundayw\LaravelSensitive;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jundayw\LaravelSensitive\Contracts\DatabaseInterface;
use Jundayw\LaravelSensitive\Contracts\InterceptorInterface;
use Jundayw\LaravelSensitive\Support\DatabaseSensitive;

class DatabaseInterceptor implements InterceptorInterface
{
    public function __construct(protected ?DatabaseInterface $database = null)
    {
        $this->database = $this->database ?? new DatabaseSensitive();
    }

    public function handle(string $content, string $field): \Illuminate\Support\Collection
    {
        $key = sprintf('%s::%s', static::class, $field);
        return cache()
            ->store(config('sensitive.cache.driver'))
            ->remember($key, config('sensitive.cache.ttl'), function () use ($field): Collection {
                return $this->database
                    ->select('keywords', $field, 'replacement')
                    ->where($field, '<>', $this->database::VALUE_PASS)
                    ->get();
            })
            ->where(function (Model $model) use ($content, $field) {
                $keywords = $model->getAttribute('keywords');
                // 转义元字符并生成正则
                $keywords = sprintf('/%s/iu', addcslashes($keywords, '\/^$()[]{}|+?.*'));
                // 将 {n} 转换为 .{0,n}
                $pattern = preg_replace(['/\\\{(\d{1,},\d{1,})\\\}/', '/\\\{(\d{1,})\\\}/',], ['.{${1}}', '.{0,${1}}',], $keywords);
                // 匹配测试
                if (preg_match($pattern, $content, $matches)) {
                    return $model->setRawAttributes([
                        'stop_type'   => $model->getAttribute($field),
                        'stop_words'  => current($matches),
                        'replacement' => $model->getAttribute('replacement'),
                    ]);
                }
                return false;
            })
            ->map(function (Model $model) {
                return $model->toArray();
            });
    }

}
