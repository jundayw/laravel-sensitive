<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $prefix = DB::connection(config('sensitive.database.connection'))->getTablePrefix();
        $table  = config('sensitive.database.table', 'sensitive');
        $query  = <<<EOT
DROP TABLE IF EXISTS `{$prefix}{$table}`;
CREATE TABLE `{$prefix}{$table}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '敏感词或查找方式，如：w{2}x{2}',
  `username` enum('PASS','BLOCK') COLLATE utf8mb4_unicode_ci DEFAULT 'PASS' COMMENT '用户名{PASS:正常}{BLOCK:黑名单}',
  `usernick` enum('PASS','REVIEW','BLOCK') COLLATE utf8mb4_unicode_ci DEFAULT 'PASS' COMMENT '昵称{PASS:正常}{REVIEW:审核}{BLOCK:黑名单}',
  `message` enum('PASS','REPLACE','BLOCK') COLLATE utf8mb4_unicode_ci DEFAULT 'PASS' COMMENT '消息{PASS:正常}{REPLACE:替换}{BLOCK:黑名单}',
  `content` enum('PASS','REPLACE','REVIEW','BLOCK') COLLATE utf8mb4_unicode_ci DEFAULT 'PASS' COMMENT '内容{PASS:正常}{REPLACE:替换}{REVIEW:审核}{BLOCK:黑名单}',
  `replacement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '***' COMMENT '替换词或替换规则',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `usernick` (`usernick`),
  KEY `message` (`message`),
  KEY `content` (`content`)
) ENGINE=InnoDB AUTO_INCREMENT=2407 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='敏感词';
EOT;
        DB::connection(config('sensitive.database.connection'))->unprepared($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::setConnection(config('sensitive.database.connection'))->dropIfExists(config('sensitive.database.table', 'sensitive'));
    }
};
