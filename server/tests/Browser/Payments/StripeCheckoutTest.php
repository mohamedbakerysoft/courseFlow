<?php

namespace Tests\Browser\Payments;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StripeCheckoutTest extends DuskTestCase
{
    protected function signPayload(string $payload): string
    {
        $ts = (string) time();
        $secret = config('services.stripe.webhook_secret');
        $sig = hash_hmac('sha256', $ts . '.' . $payload, $secret);
        return "t={$ts},v1={$sig}";
    }

    public function test_paid_checkout_enrolls_on_webhook(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);
        config()->set('services.stripe.webhook_secret', 'test_webhook_secret');

        $user = User::updateOrCreate(['email' => 'payer@example.com'], [
            'name' => 'Payer',
            'password' => bcrypt('password'),
            'role' => \App\Models\User::ROLE_STUDENT,
        ]);

        $course = Course::updateOrCreate(['slug' => 'paid-course'], [
            'title' => 'Paid Course',
            'description' => 'Desc',
            'price' => 25.00,
            'currency' => 'USD',
            'is_free' => false,
            'status' => \App\Models\Course::STATUS_PUBLISHED,
            'language' => 'en',
        ]);

        $this->browse(function (Browser $browser) use ($user, $course) {
            $browser->visit('/login')
                ->type('email', 'payer@example.com')
                ->type('password', 'password')
                ->click('button[type="submit"]')
                ->visit('/courses/'.$course->slug)
                ->waitForText('Buy Course', 10)
                ->press('Buy Course');

            // Retrieve session id from DB (pending payment created)
            $payment = Payment::where('user_id', $user->id)->where('course_id', $course->id)->latest()->first();
            $sessionId = $payment?->stripe_session_id ?? 'sess_fake';

            $payload = json_encode([
                'type' => 'checkout.session.completed',
                'data' => [
                    'object' => [
                        'id' => $sessionId,
                        'metadata' => [
                            'user_id' => (string) $user->id,
                            'course_id' => (string) $course->id,
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR);
            $sig = $this->signPayload($payload);

            $opts = [
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/json\r\nStripe-Signature: {$sig}\r\n",
                    'content' => $payload,
                    'ignore_errors' => true,
                ],
            ];
            $context = stream_context_create($opts);
            $base = rtrim(config('app.url'), '/');
            file_get_contents($base.'/webhooks/stripe', false, $context);

            $browser->visit('/courses/'.$course->slug)
                ->waitForText('You are enrolled', 10)
                ->assertSee('You are enrolled');
        });
    }
}
