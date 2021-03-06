<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKursusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kursuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('instruktur_id');
            $table->string('judul_kursus')->notNullable();
            $table->string('foto')->notNullable();
            $table->string('harga_kursus')->notNullable();
            $table->enum('tipe_kursus', ['premium', 'free']);
            $table->timestamps();
        });

        Schema::table('kursuses', function(Blueprint $kolom){
            $kolom->foreign('instruktur_id')
            ->references('id')
            ->on('instrukturs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kursuses');
        Schema::dropIfExists('kelas');
    }
}
