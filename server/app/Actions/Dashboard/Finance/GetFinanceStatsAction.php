<?php

namespace App\Actions\Dashboard\Finance;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class GetFinanceStatsAction
{
    public function execute(): array
    {
        $allTimeSales = (float) Payment::query()
            ->where('status', Payment::STATUS_PAID)
            ->sum('amount');

        $monthStart = Carbon::now()->startOfMonth();
        $monthSales = (float) Payment::query()
            ->where('status', Payment::STATUS_PAID)
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $bestSelling = Payment::query()
            ->selectRaw('course_id, COUNT(*) as cnt')
            ->where('status', Payment::STATUS_PAID)
            ->groupBy('course_id')
            ->orderByDesc('cnt')
            ->first();

        $bestSellingCourse = null;
        if ($bestSelling) {
            $course = Course::query()->find($bestSelling->course_id);
            if ($course) {
                $bestSellingCourse = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'count' => (int) $bestSelling->cnt,
                ];
            }
        }

        $salesPerCourse = Payment::query()
            ->selectRaw('course_id, COUNT(*) as cnt')
            ->where('status', Payment::STATUS_PAID)
            ->groupBy('course_id')
            ->get()
            ->map(function ($row) {
                $course = Course::query()->find($row->course_id);

                return [
                    'course_id' => $row->course_id,
                    'title' => $course?->title ?? '-',
                    'count' => (int) $row->cnt,
                ];
            })->all();

        return [
            'all_time_sales' => $allTimeSales,
            'month_sales' => $monthSales,
            'best_selling_course' => $bestSellingCourse,
            'sales_per_course' => $salesPerCourse,
        ];
    }
}
