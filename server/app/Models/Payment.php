<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'provider',
        'amount',
        'currency',
        'status',
        'created_at',
        'updated_at',
        'stripe_session_id',
        'external_reference',
        'payment_reference',
        'proof_path',
        'submitted_at',
        'approved_by',
        'approved_at',
        'review_notes',
        'rejected_at',
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    protected $casts = [
        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! filled($this->proof_path)) {
            return null;
        }

        $normalized = ltrim((string) $this->proof_path, '/');

        if (str_starts_with($normalized, 'storage/')) {
            return asset($normalized);
        }

        return asset('storage/'.$normalized);
    }

    public function getIsManualSubmissionCompleteAttribute(): bool
    {
        return filled($this->payment_reference) && filled($this->proof_path);
    }

    public static function allTimePaidTotal(): float
    {
        return (float) static::query()
            ->where('status', static::STATUS_PAID)
            ->sum('amount');
    }

    public static function monthPaidTotal(): float
    {
        return (float) static::query()
            ->where('status', static::STATUS_PAID)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('amount');
    }

    public static function bestSellingCourseSummary(): ?array
    {
        $bestSelling = static::query()
            ->join('courses', 'payments.course_id', '=', 'courses.id')
            ->selectRaw('payments.course_id, courses.title, COUNT(*) as cnt')
            ->where('payments.status', static::STATUS_PAID)
            ->groupBy('payments.course_id', 'courses.title')
            ->orderByDesc('cnt')
            ->first();

        if (! $bestSelling) {
            return null;
        }

        return [
            'id' => (int) $bestSelling->course_id,
            'title' => (string) $bestSelling->title,
            'count' => (int) $bestSelling->cnt,
        ];
    }

    public static function paginateSalesPerCourse(int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->join('courses', 'payments.course_id', '=', 'courses.id')
            ->selectRaw('payments.course_id, courses.title, COUNT(*) as cnt')
            ->where('payments.status', static::STATUS_PAID)
            ->groupBy('payments.course_id', 'courses.title')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->paginate($perPage, ['payments.course_id', 'courses.title'], 'sales_page');
    }

    public static function paginateManualRequests(int $perPage = Course::DEFAULT_PER_PAGE): LengthAwarePaginator
    {
        return static::query()
            ->with(['user', 'course', 'approver'])
            ->where('provider', 'manual')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'failed' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'manual_page');
    }

    public static function pendingManualRequestsCount(): int
    {
        return (int) static::query()
            ->where('provider', 'manual')
            ->where('status', static::STATUS_PENDING)
            ->count();
    }

    public static function recentManualRequests(int $limit = 5): Collection
    {
        return static::query()
            ->with(['user', 'course'])
            ->where('provider', 'manual')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public static function recentPaidPayments(int $limit = 5): Collection
    {
        return static::query()
            ->with(['user', 'course'])
            ->where('status', static::STATUS_PAID)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }
}
