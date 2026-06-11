<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSessionHistory extends Model
{
    use HasFactory;

    protected $table = 'user_session_histories';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'session_token',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'last_activity',
        'is_active'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'last_activity' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
