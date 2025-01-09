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
            DROP TRIGGER IF EXISTS insert_to_enrollment_updates_after_create;
        ');

        DB::unprepared('
            DROP TRIGGER IF EXISTS insert_to_enrollment_updates_after_update;
        ');

        DB::unprepared('
            DROP TRIGGER IF EXISTS insert_to_enrollment_updates_after_delete;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            CREATE TRIGGER insert_to_enrollment_updates_after_create
            AFTER INSERT ON enrollments
            FOR EACH ROW
            BEGIN
                INSERT INTO enrollment_updates (enrollment_id, created_at, updated_at)
                VALUES (NEW.id, NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER insert_to_enrollment_updates_after_update
            AFTER UPDATE ON enrollments
            FOR EACH ROW
            BEGIN
                INSERT INTO enrollment_updates (enrollment_id, created_at, updated_at)
                VALUES (OLD.id, NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER insert_to_enrollment_updates_after_delete
            AFTER DELETE ON enrollments
            FOR EACH ROW
            BEGIN
                INSERT INTO enrollment_updates (enrollment_id, created_at, updated_at)
                VALUES (OLD.id, NOW(), NOW());
            END;
        ');
    }
};
