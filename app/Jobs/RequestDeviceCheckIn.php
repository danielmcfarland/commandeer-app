<?php

namespace App\Jobs;

use App\Models\Enrollment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RequestDeviceCheckIn implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $enrollment_id,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $enrollment = Enrollment::find($this->enrollment_id)->sole();

        $enrollment->requestCheckin();
    }
}
