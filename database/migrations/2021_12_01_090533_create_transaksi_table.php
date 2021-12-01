<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('kursus_id');
            $table->string('total_price');
            $table->datetime('tanggal_pembelian');            
            $table->enum('status_transaksi', ['aktif', 'menunggu'])->default('menunggu');
            $table->timestamps();
        });

        Schema::table('transaksis', function(Blueprint $kolom){
            $kolom->foreign('user_id')
            ->references('id')
            ->on('users');
        });

        Schema::table('transaksis', function(Blueprint $kolom){
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
        Schema::dropIfExists('transaksis');
    }
}
