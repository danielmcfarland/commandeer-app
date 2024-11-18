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
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();
            $table->foreignId('enrollment_id')
                ->references('id')
                ->on('enrollments')
                ->cascadeOnDelete();
            $table->string('command_uuid')
                ->unique()
                ->index();
            $table->string('type')
                ->index();
            $table->longText('payload_raw');
            $table->json('payload')
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};
