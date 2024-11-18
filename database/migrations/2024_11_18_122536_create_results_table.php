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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_id')
                ->references('id')
                ->on('organisations')
                ->cascadeOnDelete();
            $table->foreignId('command_id')
                ->references('id')
                ->on('commands')
                ->cascadeOnDelete();
            $table->string('status')
                ->index();
            $table->longText('response_raw');
            $table->json('response')
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
        Schema::dropIfExists('results');
    }
};
