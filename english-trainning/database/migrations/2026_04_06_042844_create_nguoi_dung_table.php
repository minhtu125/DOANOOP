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
         Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('hoTen');
            $table->string('email')->unique();
            $table->string('matKhau')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->enum('mucTieu', [
                'ielts', 'toeic', 'giao_tiep', 'business', 'hoc_sinh'
            ])->default('giao_tiep');
            $table->enum('capDo', ['A1','A2','B1','B2','C1','C2'])->default('A1');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
 
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};