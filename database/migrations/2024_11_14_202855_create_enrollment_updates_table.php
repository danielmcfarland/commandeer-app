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
        Schema::create('enrollment_updates', function (Blueprint $table) {
            $table->id();
            $table->string('enrollment_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_updates');
    }
};
