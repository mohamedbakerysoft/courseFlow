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

    public const ROLE_ADMIN = 'admin';

    public const ROLE_STUDENT = 'student';

    public const PROTECTED_ADMIN_EMAIL = 'admin@example.com';

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
        'profile_image_path',
        'bio',
        'social_links',
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
            'social_links' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->is_demo) {
                abort(403);
            }
        });
        static::saving(function (User $user) {
            if ($user->email === config('demo.admin_email', self::PROTECTED_ADMIN_EMAIL) && $user->role !== self::ROLE_ADMIN) {
                abort(403);
            }
        });
    }

    public function getIsDemoAttribute(): bool
    {
        $demoEmails = [
            config('demo.admin_email', self::PROTECTED_ADMIN_EMAIL),
            'instructor@demo.com',
            'student@demo.com',
        ];
        if (str_ends_with($this->email, '@demo.com')) {
            $demoEmails[] = $this->email;
        }
        return in_array($this->email, $demoEmails, true);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user_progress')
            ->withPivot('completed_at')
            ->withTimestamps();
    }
}
