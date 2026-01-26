<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'granted',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'granted' => 'boolean',
    ];

    /**
     * Get the user that owns this permission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
