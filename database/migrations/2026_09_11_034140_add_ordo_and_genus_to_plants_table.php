<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            // Menambahkan ordo dan genus
            $table->string('ordo')->nullable()->after('family');
            $table->string('genus')->nullable()->after('ordo');
        });
    }

    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['ordo', 'genus']);
        });
    }
};