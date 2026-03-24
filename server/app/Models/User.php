<?php

namespace App\Models;

use App\Support\MediaAsset;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
            'is_disabled' => 'boolean',
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

    public function getProfileImageUrlAttribute(): string
    {
        return MediaAsset::url($this->profile_image_path, MediaAsset::avatarFallbackPath($this->email ?: $this->name));
    }

    public function getProfileImageFallbackUrlAttribute(): string
    {
        return MediaAsset::avatarFallback($this->email ?: $this->name);
    }

    public static function paginateForDashboard(int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->orderByDesc('created_at')
            ->select(['id', 'name', 'email', 'role', 'is_disabled'])
            ->paginate($perPage);
    }

    public static function primaryInstructor(): ?self
    {
        return static::query()
            ->where('email', config('demo.admin_email', self::PROTECTED_ADMIN_EMAIL))
            ->first()
            ?: static::query()->where('role', self::ROLE_ADMIN)->first();
    }

    public static function primaryInstructorOrFail(): self
    {
        return static::primaryInstructor() ?? static::query()->where('role', self::ROLE_ADMIN)->firstOrFail();
    }

    public static function findByEmail(string $email): ?self
    {
        return static::query()
            ->where('email', $email)
            ->first();
    }

    public function enrolledCoursesList(): Collection
    {
        return $this->courses()
            ->select(['courses.id', 'courses.slug', 'courses.title'])
            ->get();
    }

    public function enrolledCoursesForDashboard(): Collection
    {
        return $this->courses()
            ->published()
            ->courses()
            ->with('instructor')
            ->withCount('lessons')
            ->orderByDesc('enrollments.enrolled_at')
            ->get([
                'courses.id',
                'courses.slug',
                'courses.title',
                'courses.description',
                'courses.thumbnail_path',
                'courses.price',
                'courses.currency',
                'courses.is_free',
                'courses.language',
                'courses.instructor_id',
                'courses.product_type',
            ]);
    }

    public static function distinctEnrolledCountForCourses(array $courseIds): int
    {
        if ($courseIds === []) {
            return 0;
        }

        return (int) DB::table('enrollments')
            ->whereIn('course_id', $courseIds)
            ->distinct('user_id')
            ->count('user_id');
    }

    public static function recentStudents(int $limit = 5): Collection
    {
        return static::query()
            ->where('role', self::ROLE_STUDENT)
            ->latest('created_at')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'created_at']);
    }
}
