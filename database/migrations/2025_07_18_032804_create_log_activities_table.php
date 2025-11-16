<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // CREATE, UPDATE, DELETE
            $table->string('table_name'); // mobils, transaksis, penyewas
            $table->unsignedBigInteger('record_id'); // ID dari record yang diubah
            $table->text('description'); // Deskripsi aktivitas
            $table->json('old_data')->nullable(); // Data lama (untuk UPDATE dan DELETE)
            $table->json('new_data')->nullable(); // Data baru (untuk CREATE dan UPDATE)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'created_at']);
            $table->index(['table_name', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
