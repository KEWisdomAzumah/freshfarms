<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Override;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password_hash',
        'profile_photo_url',
        'region',
        'district',
        'communty',
        'account_status',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at'
    ];

    protected $hidden = [
        'password_hash'
    ];

    protected function casts():array{
        return[
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'delete_at' => 'datetime',
            'failed_login_attempts' => 'integer'
        ];
    }

    #[Override]
    public function getAuthPassword():string{
        return $this -> password_hash;
    }

    public function role(): BelongsTo{
        return $this -> belongsTo(Role::class, 'role_id', 'role_id');
    }

}
