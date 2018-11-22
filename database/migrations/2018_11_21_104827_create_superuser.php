<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperuser extends Migration
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
                'email' => 'admin@info.world',
                'password' => \Illuminate\Support\Facades\Hash::make('admin'),
                'first_name' => '',
                'last_name' => '',
                'phone' => '',
                'role_id' => 1
            ]
        ];

        \Illuminate\Support\Facades\DB::table('users')->insert($arr);
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
