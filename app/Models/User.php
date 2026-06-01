<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'ai_dashboard_analysis', 'ai_analysis_cached_at','is_admin', 'last_seen_at', 'seconds_spent'  ] ;

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ai_analysis_cached_at' => 'datetime',
        ];
    }

    /**
     * Define the relationship: A User has many Tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    /**
 * Convert tracked seconds into a readable hour/minute format for the admin dashboard.
 */
public function getTimeSpentAttribute()
{
    $hours = floor($this->seconds_spent / 3600);
    $minutes = floor(($this->seconds_spent / 60) % 60);

    if ($hours > 0) {
        return "{$hours}h {$minutes}m";
    }
    return "{$minutes} mins";
}
}