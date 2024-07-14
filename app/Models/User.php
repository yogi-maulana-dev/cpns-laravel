<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperadmin()
    {
        return $this->role === UserRole::SUPERADMIN();
    }

    public function isOperatorUjian()
    {
        return $this->role === UserRole::OPERATOR_UJIAN();
    }

    public function isOperatorSoal()
    {
        return $this->role === UserRole::OPERATOR_SOAL();
    }

    public function isParticipant()
    {
        return $this->role === UserRole::PARTICIPANT();
    }

    public function lazyLoadParticipant()
    {
        return $this->relationLoaded('participant') ?
            $this->participant :
            $this->load('participant')->participant;
    }

    public function scopeWithoutMe(Builder $query)
    {
        $query->whereNot('id', auth()->user()->id);
    }

    public function scopeOnlyOperatorUjian(Builder $query)
    {
        $query->where('role', UserRole::OPERATOR_UJIAN());
    }

    public function scopeSuperadmin(Builder $query)
    {
        $query->where('role', UserRole::SUPERADMIN());
    }

    public function scopeOperatorSoal(Builder $query)
    {
        $query->where('role', UserRole::OPERATOR_SOAL());
    }

    public function scopePeserta(Builder $query)
    {
        $query->where('role', UserRole::PARTICIPANT());
    }

    public function participant(): HasOne
    {
        return $this->hasOne(Participant::class);
    }
}
