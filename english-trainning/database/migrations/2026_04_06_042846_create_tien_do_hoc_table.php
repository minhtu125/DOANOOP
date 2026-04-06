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
        Schema::create('tien_do_hoc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoiDungId')
                  ->constrained('nguoi_dung')
                  ->cascadeOnDelete();
            $table->foreignId('tuVungId')
                  ->constrained('tu_vung')
                  ->cascadeOnDelete();
            $table->float('easeFactor')->default(2.5);       
            $table->integer('interval')->default(0);          
            $table->integer('repetition')->default(0);       
            $table->timestamp('ngayOnTiep');                 
            $table->timestamps();
 
           
            $table->unique(['nguoiDungId', 'tuVungId']);
 
            $table->index('ngayOnTiep');
            $table->index(['nguoiDungId', 'ngayOnTiep']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tien_do_hoc');
    }
};