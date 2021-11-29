<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomentarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komentars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('materi_id');
            $table->string('isi_komentar');
            $table->timestamps();
        });

        Schema::table('komentars', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('komentars', function(Blueprint $kolom){
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
        Schema::dropIfExists('komentars');
    }
}
