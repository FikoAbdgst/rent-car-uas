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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mobil_id')->nullable()->constrained('mobils')->onDelete('set null');

            $table->enum('kategori', [
                'fuel',         // Bahan bakar
                'maintenance',  // Perawatan/Service
                'repair',       // Perbaikan
                'insurance',    // Asuransi
                'tax',          // Pajak
                'cleaning',     // Cuci mobil
                'tire',         // Ban
                'spare_parts',  // Suku cadang
                'license',      // STNK/KIR
                'parking',      // Parkir
                'toll',         // Tol
                'operational',  // Operasional lainnya
                'other'         // Lainnya
            ]);

            $table->string('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');

            $table->enum('metode_pembayaran', [
                'cash',
                'transfer',
                'debit_card',
                'credit_card',
                'e_wallet'
            ]);

            $table->string('bukti_pembayaran')->nullable(); // Path ke file bukti
            $table->string('nomor_referensi')->nullable(); // Nomor transaksi/referensi
            $table->enum('status', ['wait', 'proses', 'selesai'])->default('wait');
            $table->text('catatan')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk optimasi query
            $table->index(['kategori', 'tanggal']);
            $table->index(['user_id', 'tanggal']);
            $table->index(['status', 'tanggal']);
            $table->index(['mobil_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
