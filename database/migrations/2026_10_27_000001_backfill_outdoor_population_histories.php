<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plants')
            ->where('outdoor_population', '>', 0)
            ->orderBy('id')
            ->eachById(function (object $plant): void {
                $hasOutdoorHistory = DB::table('plant_population_histories')
                    ->where('plant_id', $plant->id)
                    ->where('location_type', 'outdoor')
                    ->exists();

                if ($hasOutdoorHistory) {
                    return;
                }

                $firstHistory = DB::table('plant_population_histories')
                    ->where('plant_id', $plant->id)
                    ->orderBy('changed_at')
                    ->orderBy('id')
                    ->first();

                $changedAt = $firstHistory
                    ? Carbon::parse($firstHistory->changed_at)->subSecond()
                    : ($plant->created_at ? Carbon::parse($plant->created_at) : now());
                $outdoorPopulation = (int) $plant->outdoor_population;

                DB::table('plant_population_histories')->insert([
                    'plant_id' => $plant->id,
                    'user_id' => null,
                    'recorder_name' => 'System',
                    'change_type' => 'add',
                    'location_type' => 'outdoor',
                    'change_amount' => $outdoorPopulation,
                    'population_before' => 0,
                    'population_after' => $outdoorPopulation,
                    'greenhouse_population_before' => 0,
                    'greenhouse_population_after' => 0,
                    'outdoor_population_before' => 0,
                    'outdoor_population_after' => $outdoorPopulation,
                    'reason' => 'initial_stock',
                    'other_reason' => null,
                    'notes' => 'Stok awal Outdoor dari pemisahan data lokasi.',
                    'documentation_photo' => null,
                    'changed_at' => $changedAt,
                    'created_at' => $changedAt,
                    'updated_at' => $changedAt,
                ]);
            });
    }

    public function down(): void
    {
        DB::table('plant_population_histories')
            ->where('recorder_name', 'System')
            ->where('reason', 'initial_stock')
            ->where('notes', 'Stok awal Outdoor dari pemisahan data lokasi.')
            ->delete();
    }
};
