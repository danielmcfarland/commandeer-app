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

    protected $deviceInfoKeys = [
        'build_version' => 'BuildVersion',
        'device_name' => 'DeviceName',
        'model' => 'Model',
        'model_name' => 'ModelName',
        'os_version' => 'OSVersion',
        'product_name' => 'ProductName',
        'serial_number' => 'SerialNumber',
        'udid' => 'UDID',
    ];

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

    public function getAttribute($key)
    {
        $attribute = parent::getAttribute($key);

        if (in_array($key, array_keys($this->deviceInfoKeys))) {
            try {
                return $this->deviceInformation()
                    ->where('key', $this->deviceInfoKeys[$key])
                    ->sole()
                    ->value;
            } catch (Exception) {
                return '-';
            }
        }

        return $attribute;
    }
}
