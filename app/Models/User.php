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
    public function getInitialsAttribute()
    {
        $name = trim($this->name);
        $words = explode(' ', $name);
        $initials = '';

        if (count($words) > 0) {
            // İlk kelimenin baş harfi
            $initials .= mb_substr($words[0], 0, 1, 'UTF-8');

            // Eğer birden fazla kelime varsa, en son kelimenin baş harfini de al
            if (count($words) > 1) {
                $initials .= mb_substr(end($words), 0, 1, 'UTF-8');
            }
        }

        // Hepsini büyük harfe çevir
        return mb_strtoupper($initials, 'UTF-8');
    }
}
