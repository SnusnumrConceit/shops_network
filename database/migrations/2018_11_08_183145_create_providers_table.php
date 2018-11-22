<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id')
                ->comment('идентификатор поставщика');
            $table->string('name', 100)
                ->comment('наименование поставщика');
            $table->string('legal_address')
                ->comment('юридический адрес');
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
        Schema::dropIfExists('providers');
    }
}
