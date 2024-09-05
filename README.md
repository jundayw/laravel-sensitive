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
