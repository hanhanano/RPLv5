<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'team',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    // Helper untuk cek apakah user punya akses ke tim tertentu
    public function hasTeamAccess($teamName)
    {
        if ($this->role === 'admin') {
            return true;
        }

        if (in_array($this->role, ['ketua_tim', 'operator'])) {
            return $this->team === $teamName;
        }

        if ($this->role === 'viewer') {
            return true;
        }

        return false;
    }

    // Cek apakah user bisa edit publikasi tertentu
    public function canManagePublication($publication)
    {
        if ($this->role === 'admin') {
            return true;
        }

        if (in_array($this->role, ['ketua_tim', 'operator'])) {
            return $this->team === $publication->publication_pic;
        }

        return false;
    }
}