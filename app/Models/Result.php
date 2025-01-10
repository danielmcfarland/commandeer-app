<?php

namespace App\Models;

use CFPropertyList\CFPropertyList;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function command(): BelongsTo
    {
        return $this->belongsTo(Command::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->command->enrollment();
    }

    protected function response(): Attribute
    {
        return Attribute::make(
            get: function () {
                $plist = new CFPropertyList();
                $plist->parse($this->response_raw);

                return $plist->toArray();
            },
        );
    }

    public function process(): void
    {
        $result = $this->fresh();

        if (!$result->command || $result->command->type !== 'DeviceInformation') {
            return; // not handling non 'DeviceInformation' results yet
        }

        if (!array_key_exists('QueryResponses', $result->response)) {
            return;
        }

        foreach ($result->response['QueryResponses'] as $key => $value) {
            if (!$result->enrollment) {
                continue;
            }

            $result->enrollment->deviceInformation()->updateOrCreate(
                [
                    'key' => $key,
                    'organisation_id' => $result->enrollment->organisation_id,
                ],
                [
                    'value' => $value,
                ]
            );
        }
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function (Result $result): void {
            $result->process();
        });
    }
}
