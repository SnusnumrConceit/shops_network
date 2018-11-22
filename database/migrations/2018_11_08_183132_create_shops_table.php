<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id')
                ->comment('идентификатор магазина');
            $table->string('name', 100)
                ->comment('наименование магазина');
            $table->string('address')
                ->comment('адрес');
            $table->string('bank_account', 100)
                ->comment('банковский счёт');
            $table->string('contact_phone', 20)
                ->comment('контактный номер телефона');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
