<?php

namespace App\Models\NanoMdm;

use App\Models\Command;
use App\Models\NanoMdm\Command as MdmCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandResult extends Model
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

    public function command(): BelongsTo
    {
        return $this->belongsTo(MdmCommand::class, 'command_uuid', 'command_uuid');
    }

    public function addResult(): void
    {
        $c = Command::where('command_uuid', $this->command_uuid)->sole();

        $c->results()->create([
            'organisation_id' => $c->organisation_id,
            'status' => $this->status,
            'response_raw' => $this->result,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);
    }
}
