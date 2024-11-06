<?php

namespace App\Jobs\MdmCommands;

use App\Jobs\RequestDeviceCheckIn;
use App\Models\NanoMdm\Device;
use App\Models\NanoMdm\Enrollment;
use CFPropertyList\CFArray;
use CFPropertyList\CFDictionary;
use CFPropertyList\CFPropertyList;
use CFPropertyList\CFString;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

abstract class MdmCommand implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    protected string $request_name;

    protected string $command_uuid;

    protected string $command;

    protected array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Device $device,
        protected bool   $isManual = true,
    )
    {
        $this->command_uuid = Str::uuid();

        $requestType = new CFDictionary;
        $requestType->add('RequestType', new CFString($this->request_name));

        foreach ($this->payload as $key => $value) {
            if (is_string($value)) {
                $requestType->add($key, new CFString($value));
            } elseif (is_array($value)) {
                $array = new CFArray;
                foreach ($value as $subValue) {
                    $array->add(new CFString($subValue));
                }
                $requestType->add($key, $array);
            }
        }

        $commandDictionary = new CFDictionary;
        $commandDictionary->add('Command', $requestType);
        $commandDictionary->add('CommandUUID', new CFString($this->command_uuid));

        $plist = new CFPropertyList;
        $plist->add($commandDictionary);

        $this->command = $plist->toXml(CFPropertyList::FORMAT_XML);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->device->enrollments()
            ->whereType('Device')
            ->whereEnabled(true)
            ->each(function (Enrollment $enrollment) {

                $totalResultsOfType = $enrollment->commands()
                    ->where('request_type', $this->request_name)
                    ->whereHas('commandResults')
                    ->count();

                $totalRequestOfType = $totalResultsOfType + $enrollment->commands()
                        ->where('request_type', $this->request_name)
                        ->doesntHave('commandResults')
                        ->count();

                if ($totalRequestOfType == $totalResultsOfType) {
                    $enrollment->commands()->create(
                        [
                            'command_uuid' => $this->command_uuid,
                            'request_type' => $this->request_name,
                            'command' => $this->command,
                        ],
                        [
                            'active' => true,
                            'priority' => 0,
                        ]
                    );
                }

                if ($this->isManual) {
                    RequestDeviceCheckIn::dispatch($enrollment);
                }
            });
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->request_name . '_' . $this->device->id;
    }
}
