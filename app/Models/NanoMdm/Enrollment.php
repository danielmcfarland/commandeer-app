<?php

namespace App\Models\NanoMdm;

use ApnsPHP\Message\CustomMessage;
use ApnsPHP\Push;
use App\Logger\ApnsPHP_Logger;
use App\Models\Device;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enrollment extends Model
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

    public function pushCert(): BelongsTo
    {
        return $this->belongsTo(PushCert::class, 'topic', 'topic');
    }

    public function commands(): BelongsToMany
    {
        return $this->belongsToMany(Command::class, 'enrollment_queue', 'id', 'command_uuid')
            ->withPivot('active', 'priority')
            ->withTimestamps();
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function requestCheckin(): void
    {
        $pushCert = $this->pushCert;

        $tempFilePath = tempnam(sys_get_temp_dir(), '.pem_cert_');
        file_put_contents($tempFilePath, $pushCert->cert_pem . "\n" . $pushCert->key_pem);

        $push = new Push(
            Push::ENVIRONMENT_PRODUCTION,
            $tempFilePath,
            new ApnsPHP_Logger
        );
        $push->connect();
        $message = new CustomMessage($this->token_hex);

        $message->setCustomProperty('mdm', $this->push_magic);

        $push->add($message);
        $push->send();

        $push->disconnect();

        unlink($tempFilePath);
    }

    public function updateOrCreateEnrollment(): void
    {
        $this->organisation->enrollments()->updateOrCreate([
            'enrollment_id' => $this->id,
            'device_id' => \App\Models\Device::where('device_id', '=', $this->device_id)->sole()->device_id,
            'type' => $this->type,
        ], [
            'last_seen_at' => $this->last_seen_at,
        ]);
    }
    public function organisation(): BelongsTo
    {
        return $this->setConnection(config('database.default'))
            ->belongsTo(Organisation::class, 'topic', 'topic');
    }
}
