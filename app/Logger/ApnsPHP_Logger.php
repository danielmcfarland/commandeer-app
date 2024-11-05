<?php

namespace App\Logger;

use Psr\Log\AbstractLogger as ApnsPHP_Log_Interface;
use Illuminate\Support\Facades\Log;

class ApnsPHP_Logger extends ApnsPHP_Log_Interface
{
    public function log($level, $message, array $context = []): void
    {
         if (config('app.env') !== 'production' || strtolower($level) !== 'info') {
            Log::$level(sprintf("%s: %s ApnsPHP[%d]: %s", date('r'), strtoupper($level), getmypid(), trim($message)));
         }
    }
}
