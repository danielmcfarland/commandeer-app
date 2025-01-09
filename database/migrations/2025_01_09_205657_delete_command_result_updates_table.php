<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'nanomdm';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('command_result_updates');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('command_result_updates', function (Blueprint $table) {
            $table->id();
            $table->string('result_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
