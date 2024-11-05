<?php

namespace App\Models;

use CFPropertyList\CFArray;
use CFPropertyList\CFDictionary;
use CFPropertyList\CFPropertyList;
use CFPropertyList\CFString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Command extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'nanomdm';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'command_uuid';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    public static function createCommand(string $request_type): Command
    {
        return self::create([
            'request_type' => $request_type,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Command $command): void {
            $command->command_uuid = $command->command_uuid ?: Str::uuid();

            if ($command->command) {
                return;
            }

            $requestType = new CFDictionary;
            $requestType->add('RequestType', new CFString($command->request_type)); // needs more config

            if ($command->request_type === 'DeviceInformation') {
                $payload = [
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
                foreach ($payload as $key => $value) {
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
            }

            $commandDictionary = new CFDictionary;
            $commandDictionary->add('Command', $requestType);
            $commandDictionary->add('CommandUUID', new CFString($command->command_uuid));

            $plist = new CFPropertyList;
            $plist->add($commandDictionary);

            $command->command = $plist->toXml(CFPropertyList::FORMAT_XML);
        });
    }

    public function enrollment(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class, 'enrollment_queue', 'command_uuid', 'id')
            ->withPivot('active', 'priority')
            ->withTimestamps();
    }
}
