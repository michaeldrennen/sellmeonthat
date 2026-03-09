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
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->string('country')->nullable()->after('zip_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->boolean('is_verified')->default(false)->after('longitude');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            $table->string('logo_path')->nullable()->after('verified_at');
            $table->integer('service_radius_miles')->nullable()->after('logo_path')->comment('Service area radius in miles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'latitude',
                'longitude',
                'is_verified',
                'verified_at',
                'logo_path',
                'service_radius_miles'
            ]);
        });
    }
};
