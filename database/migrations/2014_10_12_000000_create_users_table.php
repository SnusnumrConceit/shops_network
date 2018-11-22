<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')
                ->comment('идентификатор пользователя');
            $table->string('email', 50)
                ->unique()
                ->comment('электронная почта');
            $table->string('password')
                ->comment('пароль');
            $table->string('first_name', 50)
                ->comment('имя');
            $table->string('last_name', 100)
                ->comment('фамилия');
            $table->string('phone', 20)
                ->comment('номер телефона');
            $table->unsignedInteger('role_id')
                ->comment('идентификатор роли');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
