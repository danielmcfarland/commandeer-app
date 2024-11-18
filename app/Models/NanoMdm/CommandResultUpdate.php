<?php

namespace App\Models\NanoMdm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommandResultUpdate extends Model
{
    use SoftDeletes;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'nanomdm';

    public function commandResult(): BelongsTo
    {
        return $this->belongsTo(CommandResult::class, 'result_id', 'command_uuid');
    }
}
