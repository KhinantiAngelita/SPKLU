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
        if (! Schema::hasTable('aktivitas_notifikasis')) {
            Schema::create('aktivitas_notifikasis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('kategori'); // spklu, transaksi, kandidat, pengajuan, fs_skema, jadwal
                $table->string('judul')->nullable();
                $table->text('pesan');
                $table->string('icon')->default('bell');
                $table->string('url')->nullable();
                $table->json('target_roles')->nullable(); // null = semua role berhak melihat
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('users', 'last_read_notification_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_read_notification_at')->nullable()->after('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_notifikasis');

        if (Schema::hasColumn('users', 'last_read_notification_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_read_notification_at');
            });
        }
    }
};
