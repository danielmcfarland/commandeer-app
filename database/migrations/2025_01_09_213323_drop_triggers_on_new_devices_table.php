<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'nanomdm';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS insert_to_new_devices_after_create;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            CREATE TRIGGER insert_to_new_devices_after_create
            AFTER INSERT ON devices
            FOR EACH ROW
            BEGIN
                INSERT INTO new_devices (device_id, created_at, updated_at)
                VALUES (NEW.id, NOW(), NOW());
            END;
        ');
    }
};
