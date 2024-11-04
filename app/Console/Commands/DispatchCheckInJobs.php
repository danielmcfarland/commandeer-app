<?php

namespace App\Console\Commands;

use App\Jobs\RequestDeviceCheckIn;
use App\Models\Enrollment;
use Illuminate\Console\Command;

class DispatchCheckInJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dispatch-check-in-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command dispatches a job for each device to checkin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Enrollment::whereType('Device')->chunk(100, function ($enrollments) {
            foreach ($enrollments as $enrollment) {
                RequestDeviceCheckIn::dispatch($enrollment->id);
            }
        });
    }
}
