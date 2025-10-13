<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trains', function (Blueprint $table) {
            $table->string('train_number')->nullable();
            $table->integer('car_count')->nullable();
            $table->integer('direction')->nullable();
            $table->integer('circuit_id')->nullable();
            $table->string('destination_station_code')->nullable();
            $table->string('line_code')->nullable();
            $table->integer('seconds_at_location')->nullable();
            $table->string('service_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('trains', function (Blueprint $table) {
            $table->dropColumn([
                'train_number',
                'car_count',
                'direction',
                'circuit_id',
                'destination_station_code',
                'line_code',
                'seconds_at_location',
                'service_type',
            ]);
        });
    }
};
