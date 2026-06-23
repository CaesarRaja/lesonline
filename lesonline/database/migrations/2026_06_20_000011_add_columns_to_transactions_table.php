<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('schedule_id')->constrained()->nullOnDelete();
            $table->decimal('jumlah_dibayar', 12, 2)->nullable()->after('total_harga');
            $table->string('refund_status')->nullable()->after('status_pembayaran');
            $table->dateTime('cancelled_at')->nullable()->after('refund_status');
            $table->string('alasan_pembatalan')->nullable()->after('cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'jumlah_dibayar', 'refund_status', 'cancelled_at', 'alasan_pembatalan']);
        });
    }
};
