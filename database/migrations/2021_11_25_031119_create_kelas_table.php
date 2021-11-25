<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kursus_id');
            $table->string('judul');
            $table->string('posisi');
            $table->timestamps();
        });

        Schema::table('kelas', function(Blueprint $kolom){
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
        Schema::dropIfExists('kelas');
    }
}
