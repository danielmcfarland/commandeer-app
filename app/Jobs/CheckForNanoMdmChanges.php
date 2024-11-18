<?php

namespace App\Jobs;

use App\Models\NanoMdm\CommandResultUpdate;
use App\Models\NanoMdm\EnrollmentUpdate;
use App\Models\NanoMdm\NewDevice;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckForNanoMdmChanges implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        NewDevice::query()->each(function (NewDevice $newDevice) {
            $newDevice->device->enroll();
            $newDevice->device->automatedCheckin();
            $newDevice->delete();
        });

        EnrollmentUpdate::query()->each(function (EnrollmentUpdate $enrollmentUpdate) {
            $enrollmentUpdate->enrollment->updateOrCreateEnrollment();
            $enrollmentUpdate->delete();
        });

        CommandResultUpdate::query()->each(function (CommandResultUpdate $commandResultUpdate) {
            $commandResultUpdate->commandResult->addResult();
            $commandResultUpdate->delete();
        });
    }
}
