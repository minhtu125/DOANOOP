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
         Schema::create('tu_vung', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boTuVungId')
                  ->constrained('bo_tu_vung')
                  ->cascadeOnDelete();
            $table->string('tu');                            // Word
            $table->string('phienAm')->nullable();           // Pronunciation
            $table->string('nghiaTiengViet');                // Meaning (VN)
            $table->text('moTaTiengAnh')->nullable();        // Description (EN)
            $table->text('viDu')->nullable();                // Example sentence
            $table->string('collocation')->nullable();       // Collocations
            $table->string('tuDongNghia')->nullable();       // Related words / synonyms
            $table->text('ghiChu')->nullable();              // Note
            $table->timestamps();
 
            $table->index('boTuVungId');
            $table->index('tu');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_vung');
    }
};