<?php

use App\Console\Commands\DispatchCheckInJobs;
use Illuminate\Support\Facades\Schedule;

Schedule::command(DispatchCheckInJobs::class)->everyFifteenMinutes();
