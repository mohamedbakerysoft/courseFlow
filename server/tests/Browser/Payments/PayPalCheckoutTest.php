<?php

namespace Tests\Browser\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PayPalCheckoutTest extends DuskTestCase
{
    public function test_paypal_checkout_enrolls_on_success(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);
        config()->set('services.paypal.webhook_secret', 'test_webhook_secret');

        app(\App\Services\SettingsService::class)->set([
            'payments.stripe.enabled' => false,
            'payments.paypal.enabled' => true,
            'payments.manual.instructions' => 'Bank transfer details',
        ]);

        $user = User::updateOrCreate(['email' => 'pp@example.com'], [
            'name' => 'PP',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        $course = Course::updateOrCreate(['slug' => 'paid-course-pp'], [
            'title' => 'Paid Course PP',
            'description' => 'Desc',
            'price' => 35.00,
            'currency' => 'USD',
            'is_free' => false,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        $this->browse(function (Browser $browser) use ($user, $course) {
            $browser->visit('/login')
                ->type('email', 'pp@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/courses/'.$course->slug)
                ->waitForText('Pay with PayPal', 5)
                ->press('Pay with PayPal');

            // Fetch order id from pending payment
            $payment = Payment::where('user_id', $user->id)->where('course_id', $course->id)->where('provider', 'paypal')->latest()->first();
            $orderId = $payment?->external_reference ?? 'order_fake';
            $ts = (string) time();
            $sig = hash_hmac('sha256', $ts . '.' . $orderId, (string) config('services.paypal.webhook_secret'));

            // Simulate success callback
            $base = rtrim(config('app.url'), '/');
            $successUrl = $base.'/payments/paypal/success?order_id='.$orderId.'&t='.$ts.'&sig='.$sig;
            file_get_contents($successUrl);

            $browser->visit('/courses/'.$course->slug)
                ->waitForText('You are enrolled', 10)
                ->assertSee('You are enrolled');
        });
    }
}
