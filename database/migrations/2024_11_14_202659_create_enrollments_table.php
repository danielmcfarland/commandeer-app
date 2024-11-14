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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();
            $table->string('enrollment_id')
                ->index();
            $table->string('device_id')
                ->references('device_id')
                ->on('devices')
                ->cascadeOnDelete()
                ->index();
            $table->string('type');
            $table->string('last_seen_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
