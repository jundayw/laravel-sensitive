# Laravel Sensitive Words Filter Package

[![GitHub Tag](https://img.shields.io/github/v/tag/jundayw/laravel-sensitive)](https://github.com/jundayw/laravel-sensitive/tags)
[![Total Downloads](https://img.shields.io/packagist/dt/jundayw/laravel-sensitive?style=flat-square)](https://packagist.org/packages/jundayw/laravel-sensitive)
[![Packagist Version](https://img.shields.io/packagist/v/jundayw/laravel-sensitive)](https://packagist.org/packages/jundayw/laravel-sensitive)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/jundayw/laravel-sensitive)](https://github.com/jundayw/laravel-sensitive)
[![Packagist License](https://img.shields.io/github/license/jundayw/laravel-sensitive)](https://github.com/jundayw/laravel-sensitive)

## 安装

推荐使用 PHP 包管理工具 [Composer](https://getcomposer.org/) 软件包管理器安装：

```shell
composer require jundayw/laravel-sensitive
```

接下来，你需要使用 `vendor:publish` Artisan 命令发布的配置和迁移文件。

配置文件将会保存在 config 文件夹中：

```shell
php artisan vendor:publish --provider="Jundayw\LaravelSensitive\SensitiveServiceProvider"
```

或单独发布配置文件

```shell
php artisan vendor:publish --tag=sensitive-config
```

或单独发布迁移文件

```shell
php artisan vendor:publish --tag=sensitive-migrations
```

最后，您应该运行数据库迁移。

```shell
php artisan migrate --path=database/migrations/2022_08_31_182223_create_sensitive_table.php
```

数据填充：

```shell
php artisan db:seed --class=SensitiveSeeder
```

### 自定义迁移

如果你不想使用默认迁移，你应该在 `sensitive.php` 配置文件中将 `migration` 设置为 `false`。

您可以通过执行以下命令导出默认迁移：

```shell
php artisan vendor:publish --tag=sensitive-migrations
```

### 扩展云端过滤器

本扩展包默认支持 `Eloquent` 驱动的 `\Jundayw\LaravelSensitive\LocalInterceptor::class` 本地过滤器，

可自定义扩展 [百度云](https://ai.baidu.com/ai-doc/ANTIPORN/Vk3h6xaga)、[腾讯云](https://cloud.tencent.com/document/product/1124/51860)、[阿里云](https://help.aliyun.com/document_detail/70439.html)
的文本审核功能。

```php
<?php

namespace App\Cloud;

use Illuminate\Support\Collection;
use Jundayw\LaravelSensitive\Contracts\InterceptorInterface;

class CloudInterceptor implements InterceptorInterface
{
    public function handle(string $content, string $field): Collection
    {
        return collect([
            // [
            //     'stop_type'   => 'REPLACE',
            //     'stop_words'  => '小额贷款',
            //     'replacement' => 'xxx',
            // ],
        ]);
    }

}
```

编码实现后需要变更配置文件 `interceptor.php` 中过滤驱动 `driver` 为 `\App\Cloud\CloudInterceptor::class`。

## Getting Started

> 自定义处理敏感词

```php
<?php

use Jundayw\LaravelSensitive\Sensitive;

public function handle(SensitiveInterface $sensitive): void
{
    $content  = '本校小额贷款，安全、快捷、方便、无抵押，随机随贷，当天放款，上门服务。';
    $instance = $sensitive->content($content);

    if ($instance->isBlock()) {
        // 含有黑名单词语
    }

    if ($instance->isReview()) {
        // 含有待审核词语
    }

    if ($instance->isReplace()) {
        // 含有敏感词词语已被替换
        $content = $instance->getContent();
    }

    var_dump($content);
}
```

> 不允许含有任何敏感词

```php
<?php

use Jundayw\LaravelSensitive\Facades\Sensitive;

try {
    $content  = '本校小额贷款，安全、快捷、方便、无抵押，随机随贷，当天放款，上门服务。';
    $instance = Sensitive::listen(SensitiveInterface::STATUS_REVIEW | SensitiveInterface::STATUS_BLOCK, function () {
        throw new Exception('含有敏感词');
    })->content($content);
    var_dump($instance->getContent());
} catch (Exception $exception) {
    // $exception->getMessage();
}
```

> 将含有任何敏感词做替换处理，并通过事件做异步处理

```php
<?php

use Jundayw\LaravelSensitive\Contracts\SensitiveInterface;
use Jundayw\LaravelSensitive\Events\SensitiveReviewEvent;
use Jundayw\LaravelSensitive\Facades\Sensitive;

$content  = '本校小额贷款，安全、快捷、方便、无抵押，随机随贷，当天放款，上门服务。';
$instance = Sensitive::listen(
    SensitiveInterface::STATUS_REVIEW | SensitiveInterface::STATUS_BLOCK,
    function (string $content, string $field, int $scope, Collection $collect, SensitiveInterface $sensitive) {
        return $sensitive->replacement($content, $collect);
    })->content($content);
// 获取替换后短语
$content = $instance->getContent();
// 正常进行业务处理
$user = User::create([
    'nickname' => $content,
]);
// 通过事件对业务做异步处理
$instance->event(SensitiveInterface::STATUS_REVIEW | SensitiveInterface::STATUS_BLOCK, SensitiveReviewEvent::class);
$instance->dispatch($user, $instance->getContent(), $instance->getRaw());
```

## License

Guzzle is made available under the MIT License (MIT). Please see [License File](LICENSE) for more information.
