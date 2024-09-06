<?php

namespace Jundayw\LaravelSensitive\Concerns;

use Jundayw\LaravelSensitive\Contracts\FilterInterface;
use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;

trait SensitiveTrait
{
    public function username(string $content): FilterInterface
    {
        return $this->filter($content, $this->interceptor::FIELD_USERNAME, SensitiveInterface::STATUS_PASS | SensitiveInterface::STATUS_BLOCK);
    }

    public function nickname(string $content): FilterInterface
    {
        return $this->filter($content, $this->interceptor::FIELD_NICKNAME, SensitiveInterface::STATUS_PASS | SensitiveInterface::STATUS_REVIEW | SensitiveInterface::STATUS_BLOCK);
    }

    public function message(string $content): FilterInterface
    {
        return $this->filter($content, $this->interceptor::FIELD_MESSAGE, SensitiveInterface::STATUS_PASS | SensitiveInterface::STATUS_REVIEW | SensitiveInterface::STATUS_REPLACE);
    }

    public function content(string $content): FilterInterface
    {
        return $this->filter($content, $this->interceptor::FIELD_CONTENT);
    }

}
