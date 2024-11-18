<?php

namespace App\Models;

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
}
