<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingKursusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_kursuses', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('kursus_id');
            $table->integer('rating');
            $table->string('review');
            $table->timestamps();
        });

        Schema::table('rating_kursuses', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('rating_kursuses', function(Blueprint $kolom){
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
        Schema::dropIfExists('rating_kursuses');
    }
}
