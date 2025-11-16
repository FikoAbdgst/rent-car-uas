<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idmobil')->constrained('mobils')->onDelete('cascade');
            $table->foreignId('idpenyewa')->constrained('penyewas')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            $table->date('tanggalmulai');
            $table->date('tanggalkembali');
            $table->date('tanggaldikembalikan')->nullable();
            $table->decimal('totalharga', 10, 2);
            $table->enum('status', ['WAIT', 'PROSES', 'SELESAI'])->default('WAIT');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
