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
            DROP TRIGGER IF EXISTS insert_to_command_result_updates_after_create;
        ');

        DB::unprepared('
            DROP TRIGGER IF EXISTS insert_to_command_result_updates_after_update;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            CREATE TRIGGER insert_to_command_result_updates_after_create
            AFTER INSERT ON command_results
            FOR EACH ROW
            BEGIN
                INSERT INTO command_result_updates (result_id, created_at, updated_at)
                VALUES (NEW.command_uuid, NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER insert_to_command_result_updates_after_update
            AFTER UPDATE ON command_results
            FOR EACH ROW
            BEGIN
                INSERT INTO command_result_updates (result_id, created_at, updated_at)
                VALUES (OLD.command_uuid, NOW(), NOW());
            END;
        ');
    }
};
