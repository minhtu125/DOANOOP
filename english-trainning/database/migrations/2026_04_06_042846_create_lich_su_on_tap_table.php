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
        Schema::create('lich_su_on_tap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoiDungId')
                  ->constrained('nguoi_dung')
                  ->cascadeOnDelete();
            $table->foreignId('tuVungId')
                  ->constrained('tu_vung')
                  ->cascadeOnDelete();
            $table->enum('ketQua', ['again', 'hard', 'good', 'easy']);
            $table->timestamp('thoiGian');
 
            // Không dùng timestamps() — chỉ cần thoiGian
            $table->index(['nguoiDungId', 'thoiGian']);
            $table->index('tuVungId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_on_tap');
    }
};