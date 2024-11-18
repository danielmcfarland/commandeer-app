<?php

namespace App\Console\Commands;

use App\Models\NanoMdm\Device;
use Illuminate\Console\Command;

class AutomatedCheckin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:automated-checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command dispatches a job for each device to run specified MDM Commands.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Device::chunk(100, function ($devices) {
            foreach ($devices as $device) {
                $device->automatedCheckin();
            }
        });
    }
}
