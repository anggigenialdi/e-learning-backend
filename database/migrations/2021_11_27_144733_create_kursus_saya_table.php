<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKursusSayaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kursus_sayas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('kursus_id');
            $table->timestamps();
        });

        Schema::table('kursus_sayas', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('kursus_sayas', function(Blueprint $kolom){
            $kolom->foreign('kursus_id')
            ->references('id')
            ->on('kursuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kursus_sayas');
    }
}
