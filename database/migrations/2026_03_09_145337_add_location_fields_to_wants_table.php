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
        Schema::table('wants', function (Blueprint $table) {
            $table->string('city')->nullable()->after('expires_at');
            $table->string('state')->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('state');
            $table->string('country')->nullable()->after('zip_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->integer('radius_miles')->nullable()->after('longitude')->comment('Preferred service radius in miles');
            $table->json('image_paths')->nullable()->after('radius_miles')->comment('Array of image paths');
            $table->boolean('is_draft')->default(false)->after('image_paths');
            $table->timestamp('published_at')->nullable()->after('is_draft');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wants', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'state',
                'zip_code',
                'country',
                'latitude',
                'longitude',
                'radius_miles',
                'image_paths',
                'is_draft',
                'published_at'
            ]);
        });
    }
};
