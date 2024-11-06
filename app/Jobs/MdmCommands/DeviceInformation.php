<?php

namespace App\Jobs\MdmCommands;

class DeviceInformation extends MdmCommand
{
    protected string $request_name = 'DeviceInformation';
    protected array $payload = [
        'Queries' => [
            'BuildVersion',
            'DeviceName',
            'Model',
            'ModelName',
            'OSVersion',
            'ProductName',
            'SerialNumber',
        ],
    ];
}
