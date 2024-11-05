<?php

namespace App\Models;

use ApnsPHP\Message\CustomMessage;
use ApnsPHP\Push;
use App\Logger\ApnsPHP_Logger;
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
}
