<?php

namespace App\Jobs\MdmCommands;

class InstalledApplicationList extends MdmCommand
{
    protected string $request_name = 'InstalledApplicationList';

    protected array $payload = [];
}
