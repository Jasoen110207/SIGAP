<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration ini aman dijalankan ulang: kolom/foreign key yang sudah ada
     * dari percobaan sebelumnya yang gagal di tengah jalan akan dilewati.
     */
    public function up(): void
    {
        // Kategori pekerjaan petugas (null untuk admin & warga)
        if (! Schema::hasColumn('users', 'category_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('role');
            });
        }
        $this->addForeignKey('users', 'category_id', 'categories');

        // Petugas yang menyelesaikan laporan (jejak audit)
        if (! Schema::hasColumn('reports', 'petugas_id')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->unsignedBigInteger('petugas_id')->nullable()->after('category_id');
            });
        }
        $this->addForeignKey('reports', 'petugas_id', 'users');
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('petugas_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }

    private function addForeignKey(string $table, string $column, string $references): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $references) {
                $blueprint->foreign($column)->references('id')->on($references)->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // Foreign key sudah ada dari percobaan sebelumnya; aman dilewati.
        }
    }
};
