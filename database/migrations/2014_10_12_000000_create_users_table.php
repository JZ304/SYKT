<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xxwsyly_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid',50)->unique()->comment('OPENID');
            $table->string('unionid',50)->unique()->comment('UNIONID');
            $table->string('phone',20)->nullable()->comment('电话');
            $table->string('nickName',50)->comment('昵称');
            $table->string('avatarUrl')->comment('头像');
            $table->string('gender',4)->comment('性别');
            $table->string('city',20)->nullable()->comment('城市');
            $table->string('province',20)->nullable()->comment('省份');
            $table->string('country',20)->nullable()->comment('国家');
            $table->string('language',10)->nullable()->comment('语言');
            $table->integer('points')->default(0)->comment('积分');
            $table->tinyInteger('status')->default(1)->comment('状态 0 冻结 1启用');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE xxwsyly_users AUTO_INCREMENT = 10000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xxwsyly_users');
    }
}
