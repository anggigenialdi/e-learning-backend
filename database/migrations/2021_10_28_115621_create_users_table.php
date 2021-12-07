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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama')->notNullable();
            $table->string('email')->unique()->notNullable();
            $table->string('password');
            $table->enum('role', ['basic', 'admin'])->default('basic');
            $table->string('token', 500)->nullable();
            $table->timestamps();
        });
        
        DB::table('users')->insert(array(
            'id' => '1',
            'nama' => 'admin',
            'email' => 'admin@kursus.com',
            'password' => '$2y$10$m6JLzkjryFUCetmj3JLISuccF1PeqfhnHeysqmtW0CMq8VDbZEhBG',
            'role' => 'admin'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('profiles');
    }
}
