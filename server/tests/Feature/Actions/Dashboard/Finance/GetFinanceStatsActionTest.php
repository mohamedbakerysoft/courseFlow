<?php

use App\Actions\Dashboard\Finance\GetFinanceStatsAction;
use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

it('computes finance stats correctly', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $courseA = Course::create([
        'title' => 'Course A', 'slug' => 'course-a',
        'price' => 100, 'currency' => 'USD', 'is_free' => false,
        'status' => Course::STATUS_PUBLISHED, 'language' => 'en', 'instructor_id' => $admin->id,
    ]);
    $courseB = Course::create([
        'title' => 'Course B', 'slug' => 'course-b',
        'price' => 50, 'currency' => 'USD', 'is_free' => false,
        'status' => Course::STATUS_PUBLISHED, 'language' => 'en', 'instructor_id' => $admin->id,
    ]);
    $user = User::factory()->create();

    Payment::create([
        'user_id' => $user->id, 'course_id' => $courseA->id, 'provider' => 'stripe',
        'amount' => 100, 'currency' => 'USD', 'status' => Payment::STATUS_PAID,
        'created_at' => Carbon::now()->subDays(10), 'updated_at' => Carbon::now()->subDays(10),
    ]);
    $anotherUser = User::factory()->create();
    Payment::create([
        'user_id' => $anotherUser->id, 'course_id' => $courseA->id, 'provider' => 'paypal',
        'amount' => 100, 'currency' => 'USD', 'status' => Payment::STATUS_PAID,
        'created_at' => Carbon::now()->subMonths(2), 'updated_at' => Carbon::now()->subMonths(2),
    ]);
    Payment::create([
        'user_id' => $user->id, 'course_id' => $courseB->id, 'provider' => 'manual',
        'amount' => 50, 'currency' => 'USD', 'status' => Payment::STATUS_PAID,
        'created_at' => Carbon::now()->subDays(1), 'updated_at' => Carbon::now()->subDays(1),
    ]);
    Payment::create([
        'user_id' => $user->id, 'course_id' => $courseB->id, 'provider' => 'stripe',
        'amount' => 50, 'currency' => 'USD', 'status' => Payment::STATUS_FAILED,
    ]);

    $stats = (new GetFinanceStatsAction)->execute();

    expect($stats['all_time_sales'])->toBe(250.0);
    expect($stats['month_sales'])->toBe(150.0);
    expect($stats['best_selling_course']['title'])->toBe('Course A');
    expect(collect($stats['sales_per_course'])->count())->toBe(2);
});
