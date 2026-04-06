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
        Schema::create('bo_tu_vung', function (Blueprint $table) {
            $table->id();
            $table->string('tenBoTu');
            $table->text('moTa')->nullable();
            $table->foreignId('nguoiDungId')
                  ->constrained('nguoi_dung')
                  ->cascadeOnDelete();
            $table->json('tags')->nullable();                
            $table->boolean('laCong')->default(false);       
            $table->string('hinhAnh')->nullable();
            $table->softDeletes();
            $table->timestamps();
 
            $table->index('nguoiDungId');
            $table->index('laCong');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bo_tu_vung');
    }
};