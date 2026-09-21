<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pengelola')->after('email');
            $table->string('status')->default('active')->after('role'); // pending | active | nonaktif

            $table->string('password')->nullable()->change(); // nullable: user login-Google-only gak punya password

            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('avatar')->nullable()->after('google_id');

            // Undangan
            $table->string('invitation_token')->nullable()->unique()->after('avatar');
            $table->timestamp('invitation_expires_at')->nullable()->after('invitation_token');
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete()->after('invitation_expires_at');

            // OTP aktivasi
            $table->string('otp_code')->nullable()->after('invited_by');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->unsignedTinyInteger('otp_attempts')->default(0)->after('otp_expires_at');

            $table->timestamp('last_login_at')->nullable()->after('otp_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invited_by');
            $table->dropColumn([
                'role', 'status', 'google_id', 'avatar',
                'invitation_token', 'invitation_expires_at',
                'otp_code', 'otp_expires_at', 'otp_attempts', 'last_login_at',
            ]);
        });
    }
};