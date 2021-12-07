<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerakhirDitontonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terakhir_ditontons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('kursus_id');
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('materi_id');
            $table->timestamps();
        });

        Schema::table('terakhir_ditontons', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('terakhir_ditontons', function(Blueprint $kolom){
            $kolom->foreign('kursus_id')
            ->references('id')
            ->on('kursuses');
        });

        Schema::table('terakhir_ditontons', function(Blueprint $kolom){
            $kolom->foreign('kelas_id')
            ->references('id')
            ->on('kelas');
        });

        Schema::table('terakhir_ditontons', function(Blueprint $kolom){
            $kolom->foreign('materi_id')
            ->references('id')
            ->on('materis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terakhir_ditontons');
    }
}
