<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('owner', 'admin')
            ->withTimestamps();
    }

    public function organisationUsers(): HasMany
    {
        return $this->hasMany(OrganisationUser::class);
    }
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'id', 'id');
    }
}
