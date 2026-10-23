<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_population_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recorder_name')->default('Admin');
            $table->enum('change_type', ['add', 'subtract']);
            $table->integer('change_amount');
            $table->unsignedInteger('population_before');
            $table->unsignedInteger('population_after');
            $table->string('reason');
            $table->string('other_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('documentation_photo')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->index(['plant_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_population_histories');
    }
};
