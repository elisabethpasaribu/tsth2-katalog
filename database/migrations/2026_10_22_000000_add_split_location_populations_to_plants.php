<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->unsignedInteger('greenhouse_population')->default(0)->after('population');
            $table->unsignedInteger('outdoor_population')->default(0)->after('greenhouse_population');
        });

        Schema::table('plant_population_histories', function (Blueprint $table) {
            $table->string('location_type')->default('greenhouse')->after('change_type');
            $table->unsignedInteger('greenhouse_population_before')->nullable()->after('population_after');
            $table->unsignedInteger('greenhouse_population_after')->nullable()->after('greenhouse_population_before');
            $table->unsignedInteger('outdoor_population_before')->nullable()->after('greenhouse_population_after');
            $table->unsignedInteger('outdoor_population_after')->nullable()->after('outdoor_population_before');
        });

        DB::table('plants')->where('location_type', 'greenhouse')
            ->update(['greenhouse_population' => DB::raw('COALESCE(population, 0)')]);
        DB::table('plants')->whereIn('location_type', ['collection', 'outdoor'])
            ->update(['outdoor_population' => DB::raw('COALESCE(population, 0)')]);
    }

    public function down(): void
    {
        Schema::table('plant_population_histories', function (Blueprint $table) {
            $table->dropColumn([
                'location_type',
                'greenhouse_population_before',
                'greenhouse_population_after',
                'outdoor_population_before',
                'outdoor_population_after',
            ]);
        });

        Schema::table('plants', function (Blueprint $table) {
            $table->dropColumn(['greenhouse_population', 'outdoor_population']);
        });
    }
};
