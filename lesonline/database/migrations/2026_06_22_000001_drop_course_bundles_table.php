<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('course_bundles');
    }

    public function down(): void
    {
        // No rollback - feature removed permanently
    }
};
