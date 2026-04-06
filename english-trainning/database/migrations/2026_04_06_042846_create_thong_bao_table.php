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
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoiDungId')
                  ->constrained('nguoi_dung')
                  ->cascadeOnDelete();
            $table->enum('loai', [
                'nhac_hoc', 'on_tap', 'streak', 'he_thong'
            ])->default('nhac_hoc');
            $table->text('noiDung');
            $table->timestamp('thoiGianGui');
            $table->boolean('daDoc')->default(false);
            // timestamps() không cần thiết, dùng thoiGianGui
            $table->timestamps();
 
            $table->index(['nguoiDungId', 'daDoc']);
            $table->index('thoiGianGui');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
    }
};