<?php

namespace App\Actions\Dashboard\Finance;

use App\Models\Payment;

class GetFinanceStatsAction
{
    public function execute(): array
    {
        return [
            'all_time_sales' => Payment::allTimePaidTotal(),
            'month_sales' => Payment::monthPaidTotal(),
            'best_selling_course' => Payment::bestSellingCourseSummary(),
            'sales_per_course' => Payment::paginateSalesPerCourse(),
            'manual_payment_requests' => Payment::paginateManualRequests(),
        ];
    }
}
