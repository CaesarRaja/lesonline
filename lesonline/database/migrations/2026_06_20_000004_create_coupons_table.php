<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->enum('tipe', ['percent', 'nominal']);
            $table->decimal('nilai', 12, 2);
            $table->unsignedInteger('kuota')->default(0);
            $table->unsignedInteger('terpakai')->default(0);
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
