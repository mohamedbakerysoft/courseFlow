<?php

namespace App\Models;

use App\Support\MediaAsset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class Course extends \Illuminate\Database\Eloquent\Model
{
    public const DEFAULT_PER_PAGE = 10;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_path',
        'download_file_path',
        'price',
        'currency',
        'is_free',
        'status',
        'product_type',
        'language',
        'instructor_id',
    ];

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_DRAFT = 'draft';

    public const TYPE_COURSE = 'course';

    public const TYPE_BOOK = 'book';

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeCourses($query)
    {
        return $query->where('product_type', self::TYPE_COURSE);
    }

    public function scopeBooks($query)
    {
        return $query->where('product_type', self::TYPE_BOOK);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('position');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return MediaAsset::url($this->thumbnail_path, MediaAsset::courseFallbackPath($this->slug ?: $this->title));
    }

    public function getThumbnailFallbackUrlAttribute(): string
    {
        return MediaAsset::courseFallback($this->slug ?: $this->title);
    }

    public function getIsBookAttribute(): bool
    {
        return ($this->product_type ?? self::TYPE_COURSE) === self::TYPE_BOOK;
    }

    public static function paginatePublishedCourses(?string $priceFilter = null, int $perPage = self::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->published()
            ->courses()
            ->when($priceFilter === 'free', fn ($query) => $query->where('is_free', true))
            ->when($priceFilter === 'paid', fn ($query) => $query->where('is_free', false))
            ->with('instructor')
            ->withCount('lessons')
            ->orderByDesc('created_at')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free', 'instructor_id', 'product_type'])
            ->paginate($perPage);
    }

    public static function publishedCourseCatalogSummary(): array
    {
        $baseQuery = static::query()
            ->published()
            ->courses();

        return [
            'total' => (clone $baseQuery)->count(),
            'free' => (clone $baseQuery)->where('is_free', true)->count(),
            'paid' => (clone $baseQuery)->where('is_free', false)->count(),
        ];
    }

    public static function paginatePublishedBooks(int $perPage = self::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->published()
            ->books()
            ->with('instructor')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public static function paginateInstructorItems(User $user, ?string $productType = null, int $perPage = self::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->where('instructor_id', $user->id)
            ->when($productType, fn ($query) => $query->where('product_type', $productType))
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public static function instructorCourseIds(User $user): array
    {
        return static::query()
            ->where('instructor_id', $user->id)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public static function countForInstructor(User $user, ?string $productType = null, ?string $status = null): int
    {
        return (int) static::query()
            ->where('instructor_id', $user->id)
            ->when($productType, fn ($query) => $query->where('product_type', $productType))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->count();
    }

    public static function latestDraftForInstructor(User $user): ?self
    {
        return static::query()
            ->where('instructor_id', $user->id)
            ->where('status', self::STATUS_DRAFT)
            ->latest('updated_at')
            ->first();
    }

    public static function recentForInstructor(User $user, int $limit = 5): Collection
    {
        return static::query()
            ->where('instructor_id', $user->id)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get(['id', 'slug', 'title', 'status', 'product_type', 'updated_at']);
    }

    public static function listPublishedForLanding(int $limit = 6): Collection
    {
        return static::query()
            ->published()
            ->courses()
            ->with('instructor')
            ->withCount('lessons')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public static function listPublishedForInstructorProfile(): Collection
    {
        return static::query()
            ->published()
            ->with('instructor')
            ->withCount('lessons')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free', 'language', 'instructor_id'])
            ->get();
    }

    public static function paginatePublishedForInstructorProfile(int $perPage = 20): LengthAwarePaginator
    {
        return static::query()
            ->published()
            ->courses()
            ->with('instructor')
            ->withCount('lessons')
            ->orderByDesc('created_at')
            ->select(['id', 'slug', 'title', 'description', 'thumbnail_path', 'price', 'currency', 'is_free', 'language', 'instructor_id', 'product_type'])
            ->paginate($perPage);
    }

    public static function listPublishedOptions(): Collection
    {
        return static::query()
            ->published()
            ->select(['id', 'slug', 'title'])
            ->orderBy('title')
            ->get();
    }

    public static function findPublishedById(int $id): self
    {
        return static::query()
            ->published()
            ->whereKey($id)
            ->firstOrFail();
    }

    public function publishedLessonsList(): Collection
    {
        return $this->lessons()
            ->published()
            ->with('module')
            ->select(['id', 'course_id', 'module_id', 'slug', 'title', 'position'])
            ->orderBy('position')
            ->get();
    }
}
