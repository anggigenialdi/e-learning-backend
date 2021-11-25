<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id');
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('link_video');
            $table->bigInteger('posisi');
            $table->string('materi_sebelumnya');
            $table->string('materi_selanjutnya');
            $table->timestamps();
        });

        Schema::table('materis', function(Blueprint $kolom){
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
        Schema::dropIfExists('materis');
    }
}
