<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_products', function (Blueprint $table) {
            $table->unsignedInteger('product_id')
                ->comment('идентификатор продукта');
            $table->unsignedInteger('provider_id')
                ->comment('идентификатор поставщика');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_products');
    }
}
