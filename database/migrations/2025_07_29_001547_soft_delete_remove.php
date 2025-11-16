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
        // Remove soft delete column from penyewas table
        if (Schema::hasColumn('penyewas', 'deleted_at')) {
            Schema::table('penyewas', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }

        // Remove soft delete column from transaksis table
        if (Schema::hasColumn('transaksis', 'deleted_at')) {
            Schema::table('transaksis', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }

        // Remove soft delete column from mobils table
        if (Schema::hasColumn('mobils', 'deleted_at')) {
            Schema::table('mobils', function (Blueprint $table) {
                $table->dropColumn('deleted_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back soft delete columns if needed
        Schema::table('penyewas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('mobils', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
