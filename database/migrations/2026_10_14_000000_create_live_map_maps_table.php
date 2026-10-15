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
        Schema::create('live_map_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('type', 20)->default('custom');
            $table->string('url', 500);
            $table->unsignedInteger('server_id')->nullable();
            $table->string('icon', 100)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->foreign('server_id')->references('id')->on('servers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_map_maps');
    }
};
