<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UserBind extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_bind', function (Blueprint $table) {
            $table->unsignedInteger('id')->nullable(false)->unique('uniq_id');
            $table->unsignedInteger('userId')->nullable(false)->comment('用户id');
            $table->string('type', 64)->nullable(false)->default('')->comment('绑定类型:wechat 微信;');
            $table->string('fromId', 64)->nullable(false)->default('')->comment('来源方用户id');
            $table->string('fromKey', 64)->nullable(false)->default('')->comment('来源方用户key');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->timestamp('updatedTime')->nullable()->comment('更新时间');
            $table->timestamp('deletedTime')->nullable()->comment('删除时间');
            $table->comment('用户绑定表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bind');
    }
}
