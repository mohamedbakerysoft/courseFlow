<?php

namespace Tests\Browser\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ManualPaymentTest extends DuskTestCase
{
    public function test_manual_payment_pending_and_approval_enrolls(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);

        app(\App\Services\SettingsService::class)->set([
            'payments.stripe.enabled' => false,
            'payments.paypal.enabled' => false,
            'payments.manual.instructions' => 'Bank transfer details',
        ]);

        $student = User::updateOrCreate(['email' => 'manual@example.com'], [
            'name' => 'Manual Student',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_ADMIN,
        ]);

        $course = Course::updateOrCreate(['slug' => 'manual-course'], [
            'title' => 'Manual Course',
            'description' => 'Desc',
            'price' => 40.00,
            'currency' => 'USD',
            'is_free' => false,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        $this->browse(function (Browser $browser) use ($student, $course) {
            $browser->visit('/login')
                ->type('email', 'manual@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/courses/'.$course->slug)
                ->waitForText('Manual Payment', 5)
                ->press('Manual Payment')
                ->waitForText('Manual Payment Pending', 5)
                ->assertSee('Manual Payment Pending');

            // Approve payment via POST request as admin
            $payment = Payment::where('user_id', $student->id)->where('course_id', $course->id)->where('provider', 'manual')->latest()->first();
            $opts = [
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => '',
                    'ignore_errors' => true,
                ],
            ];
            $context = stream_context_create($opts);
            $base = rtrim(config('app.url'), '/');
            file_get_contents($base.'/dashboard/payments/'.$payment->id.'/approve', false, $context);

            $browser->visit('/courses/'.$course->slug)
                ->waitForText('You are enrolled', 10)
                ->assertSee('You are enrolled');
        });
    }
}
