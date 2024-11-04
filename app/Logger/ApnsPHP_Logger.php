<?php

namespace App\Logger;

use ApnsPHP_Log_Interface;
use Illuminate\Support\Facades\Log;

class ApnsPHP_Logger implements ApnsPHP_Log_Interface
{
    public function log($sMessage)
    {
        if (config('app.env') != 'production') {
            Log::info($sMessage);
        }
    }
}
