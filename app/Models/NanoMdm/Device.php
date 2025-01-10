<?php

namespace App\Models\NanoMdm;

use App\Jobs\MdmCommands\DeviceInformation;
use App\Jobs\MdmCommands\InstalledApplicationList;
use App\Jobs\MdmCommands\ProfileList;
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
        'authenticate',
        'token_update',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'device_id', 'id');
    }

    public function automatedCheckin(bool $runCommands = true): void
    {
        DeviceInformation::dispatchIf($runCommands, $this, false);
        InstalledApplicationList::dispatchIf($runCommands, $this, false);
        ProfileList::dispatchIf($runCommands, $this, false);

        $this->enrollments()
            ->whereType('Device')
            ->whereEnabled(true)
            ->each(function (Enrollment $enrollment) {
                RequestDeviceCheckIn::dispatch($enrollment);
            });
    }

    public function enroll(): void
    {
        $enrollment = $this->enrollments()
            ->where('Type', '=', 'Device')
            ->sole();

        $enrollment->organisation->devices()->firstOrCreate([
            'device_id' => $this->id,
            'serial_number' => $this->serial_number,
            'created_at' => $this->created_at,
        ]);

        $this->automatedCheckin(false);
    }
}
