<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasSelesaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_selesais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('kursus_id');
            $table->unsignedBigInteger('materi_id');
            $table->timestamps();
        });

        Schema::table('kelas_selesais', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('kelas_selesais', function(Blueprint $kolom){
            $kolom->foreign('kelas_id')
            ->references('id')
            ->on('kelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kelas_selesais');
    }
}
