<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('requires_otp_first_login')->default(false)->after('force_password_change');
            $table->timestamp('first_login_verified_at')->nullable()->after('requires_otp_first_login');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['requires_otp_first_login', 'first_login_verified_at']);
        });
    }
};