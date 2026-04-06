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
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoiDungId')
                  ->constrained('nguoi_dung')
                  ->cascadeOnDelete();
            $table->integer('soTuDaHoc')->default(0);
            $table->integer('streak')->default(0);
            $table->float('accuracy')->default(0.0);         
            $table->timestamp('lastStudiedAt')->nullable();
            $table->timestamps();
 
            $table->unique('nguoiDungId');                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stats');
    }
};