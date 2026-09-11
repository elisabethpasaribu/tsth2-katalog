<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('plants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('latin_name');
        
        // Kolom tambahan untuk taksonomi
        $table->string('ordo')->nullable();
        $table->string('family');
        $table->string('genus')->nullable();
        $table->string('botanist')->nullable();
        
        // Kolom tambahan untuk detail
        $table->text('benefits')->nullable();
        $table->string('origin')->nullable();

        $table->text('description_short');
        $table->text('description_full')->nullable();
        $table->string('image')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};