<?php

namespace App\Models\NanoMdm;

use App\Jobs\MdmCommands\DeviceInformation;
use App\Jobs\MdmCommands\InstalledApplicationList;
use App\Jobs\RequestDeviceCheckIn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
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
    protected $primaryKey = 'id';

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

    protected $hidden = [
        'identity_cert',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'device_id', 'id');
    }

    public function automatedCheckin()
    {
        DeviceInformation::dispatch($this, false);
        InstalledApplicationList::dispatch($this, false);

        $this->enrollments()
            ->whereType('Device')
            ->whereEnabled(true)
            ->each(function (Enrollment $enrollment) {
                RequestDeviceCheckIn::dispatch($enrollment);
            });
    }
}
