<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $arr = [
            [
                'name' => 'Администратор',
                'slug' => 'admin'
            ],
            [
                'name' => 'Менеджер',
                'slug' => 'manager'
            ]

        ];

        \Illuminate\Support\Facades\DB::table('roles')->insert($arr);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
