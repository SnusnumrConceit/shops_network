<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id')
                ->comment('идентификатор договора');
            $table->unsignedInteger('provider_id')
                ->nullable()
                ->comment('идентификатор поставщика');
            $table->unsignedInteger('author_id')
                ->nullable()
                ->comment('идентификатор заключителя');
            $table->unsignedInteger('shop_id')
                ->nullable()
                ->comment('идентификатор продукта');
            $table->timestamps();
            $table->date('deadline')
                ->comment('срок выполнения');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
