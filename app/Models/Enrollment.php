<?php

namespace App\Models;

use ApnsPHP_Abstract;
use ApnsPHP_Message_Custom;
use ApnsPHP_Push;
use App\Logger\ApnsPHP_Logger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function requestCheckin(): void
    {
        $pushCert = $this->pushCert;

        $tempFilePath = tempnam(sys_get_temp_dir(), 'cert_');
        file_put_contents($tempFilePath, $pushCert->cert_pem . $pushCert->key_pem);

        $push = new ApnsPHP_Push(
            ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
            $tempFilePath,
            ApnsPHP_Abstract::PROTOCOL_HTTP
        );
        $push->setLogger(new ApnsPHP_Logger);
        $push->connect();
        $message = new ApnsPHP_Message_Custom($this->token_hex);

        $message->setCustomProperty('mdm', $this->push_magic);

        $push->add($message);
        $push->send();

        $push->disconnect();

        unlink($tempFilePath);
    }
}
