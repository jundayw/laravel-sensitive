<?php

namespace Jundayw\LaravelSensitive\Contracts;

use Illuminate\Support\Collection;

interface InterceptorInterface
{
    public const FIELD_USERNAME = 'username';
    public const FIELD_NICKNAME = 'nickname';
    public const FIELD_MESSAGE  = 'message';
    public const FIELD_CONTENT  = 'content';

    public function handle(string $content, string $field): Collection;

}
