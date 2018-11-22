<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('set null');

            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');;

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('set null');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);

            $table->dropForeign(['author_id']);

            $table->dropForeign(['shop_id']);
        });
    }
}
