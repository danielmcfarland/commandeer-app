<?php

use App\Console\Commands\AutomatedCheckin;
use App\Console\Commands\DispatchCheckInJobs;
use App\Jobs\CheckForNewDevices;
use Illuminate\Support\Facades\Schedule;

Schedule::command(DispatchCheckInJobs::class)->everyFifteenMinutes();

Schedule::command(AutomatedCheckin::class)->dailyAt('01:00');

Schedule::job(CheckForNewDevices::class)->everyMinute();
