<?php

namespace App\Models;

use App\Models\NanoMdm\Device as MdmDevice;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'device_id', 'device_id');
    }

    public function deviceInformation(): HasMany
    {
        try {
            return $this->enrollments()
                ->where('type', 'Device')
                ->sole()
                ->deviceInformation();
        } catch (Exception) {
            return new HasMany($this->newQuery(), $this, '', '');
        }
    }

    public function commands(): HasManyThrough
    {
        return $this->hasManyThrough(
            related: Command::class,
            through: Enrollment::class,
            firstKey: 'device_id',
            secondKey: 'enrollment_id',
            localKey: 'device_id',
            secondLocalKey: 'id',
        );
    }

    protected function lastSeenAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $enrollment = $this->enrollments()->latest('last_seen_at')->first();
                return $enrollment ? $enrollment->last_seen_at : null;
            },
        );
    }

    public function mdmDevice(): hasOne
    {
        return $this->setConnection('nanomdm')
            ->hasOne(MdmDevice::class, 'id', 'device_id');
    }

    protected function deviceName(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    $enrollment = $this
                        ->enrollments()
                        ->where('type', 'Device')
                        ->sole();

                    return $enrollment ? $enrollment->deviceInformation()->where('key', 'DeviceName')->sole()->value : '-';
                } catch (Exception) {
                    return '-';
                }

            },
        );
    }
}
