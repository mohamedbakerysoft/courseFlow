<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings): View
    {
        $defaultLanguage = $settings->get('site.default_language', 'en');
        $logoPath = $settings->get('site.logo_path');

        $paymentsStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $paymentsPaypalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $paymentsManualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');

        $logoUrl = $logoPath ? asset('storage/'.$logoPath) : null;

        $landingHeroTitle = (string) $settings->get('landing.hero_title', 'Teach and sell your courses with CourseFlow');
        $landingHeroSubtitle = (string) $settings->get('landing.hero_subtitle', 'Launch a clean, modern course platform in minutes.');
        $landingFeature1Title = (string) $settings->get('landing.feature_1_title', 'Launch quickly');
        $landingFeature1Description = (string) $settings->get('landing.feature_1_description', 'Ship a polished learning platform without building everything from scratch.');
        $landingFeature2Title = (string) $settings->get('landing.feature_2_title', 'Sell courses with confidence');
        $landingFeature2Description = (string) $settings->get('landing.feature_2_description', 'Stripe, PayPal and manual payments are ready for production.');
        $landingFeature3Title = (string) $settings->get('landing.feature_3_title', 'Delight your students');
        $landingFeature3Description = (string) $settings->get('landing.feature_3_description', 'Clean lessons, progress tracking and RTL-ready layouts out of the box.');
        $landingInstructorImagePath = $settings->get('landing.instructor_image');
        $landingInstructorImageUrl = $landingInstructorImagePath ? asset('storage/'.$landingInstructorImagePath) : null;

        return view('dashboard.settings.edit', compact(
            'defaultLanguage',
            'logoUrl',
            'paymentsStripeEnabled',
            'paymentsPaypalEnabled',
            'paymentsManualInstructions',
            'landingHeroTitle',
            'landingHeroSubtitle',
            'landingFeature1Title',
            'landingFeature1Description',
            'landingFeature2Title',
            'landingFeature2Description',
            'landingFeature3Title',
            'landingFeature3Description',
            'landingInstructorImageUrl',
        ));
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'default_language' => ['required', 'in:en,ar'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'payments_stripe_enabled' => ['nullable', 'boolean'],
            'payments_paypal_enabled' => ['nullable', 'boolean'],
            'payments_manual_instructions' => ['nullable', 'string'],
            'landing_hero_title' => ['nullable', 'string', 'max:255'],
            'landing_hero_subtitle' => ['nullable', 'string', 'max:255'],
            'landing_feature_1_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_1_description' => ['nullable', 'string'],
            'landing_feature_2_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_2_description' => ['nullable', 'string'],
            'landing_feature_3_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_3_description' => ['nullable', 'string'],
            'landing_instructor_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $values = [
            'site.default_language' => $validated['default_language'],
            'payments.stripe.enabled' => $request->boolean('payments_stripe_enabled'),
            'payments.paypal.enabled' => $request->boolean('payments_paypal_enabled'),
            'payments.manual.instructions' => $validated['payments_manual_instructions'] ?? '',
            'landing.hero_title' => $validated['landing_hero_title'] ?? '',
            'landing.hero_subtitle' => $validated['landing_hero_subtitle'] ?? '',
            'landing.feature_1_title' => $validated['landing_feature_1_title'] ?? '',
            'landing.feature_1_description' => $validated['landing_feature_1_description'] ?? '',
            'landing.feature_2_title' => $validated['landing_feature_2_title'] ?? '',
            'landing.feature_2_description' => $validated['landing_feature_2_description'] ?? '',
            'landing.feature_3_title' => $validated['landing_feature_3_title'] ?? '',
            'landing.feature_3_description' => $validated['landing_feature_3_description'] ?? '',
        ];

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $values['site.logo_path'] = $path;
        }

         if ($request->hasFile('landing_instructor_image')) {
             $path = $request->file('landing_instructor_image')->store('landing', 'public');
             $values['landing.instructor_image'] = $path;
         }

        $settings->set($values);

        return back()->with('status', 'Settings updated.');
    }
}
