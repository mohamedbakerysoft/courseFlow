<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Payments\ValidatePayPalConfigAction;
use App\Actions\Payments\ValidateStripeConfigAction;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use App\Support\LandingContent;
use App\Support\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings, ValidateStripeConfigAction $stripeValidator, ValidatePayPalConfigAction $paypalValidator): View
    {
        $defaultLanguage = 'en';
        $defaultTheme = (string) $settings->get('ui.theme.default', 'system');
        $demoEnabled = filter_var($settings->get('demo.enabled', config('demo.enabled')), FILTER_VALIDATE_BOOL);
        $logoPath = $settings->get('site.logo_path');
        $securityRightClickEnabled = (bool) $settings->get('security.right_click.enabled', true);

        $paymentsStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $paymentsPaypalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $paymentsManualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');

        $logoUrl = $logoPath ? asset('storage/'.$logoPath) : null;

        $instructorName = (string) $settings->get('instructor.name', '');
        $landingHeroTitle = (string) $settings->get('landing.hero_title', 'Launch courses with a storefront learners trust');
        $landingHeroSubtitle = (string) $settings->get('landing.hero_subtitle', 'Sell digital courses with secure checkout, instant access, and structured lessons.');
        $landingHeroTitleEn = (string) $settings->get('landing.hero_title_en', '');
        $landingHeroSubtitleEn = (string) $settings->get('landing.hero_subtitle_en', '');
        $heroTitleEn = (string) $settings->get('hero.title.en', '');
        $heroSubtitleEn = (string) $settings->get('hero.subtitle.en', '');
        $landingFeature1Title = (string) $settings->get('landing.feature_1_title', 'Secure checkout');
        $landingFeature1Description = (string) $settings->get('landing.feature_1_description', 'Offer card, PayPal, or manual payments without confusing the learner.');
        $landingFeature2Title = (string) $settings->get('landing.feature_2_title', 'Structured delivery');
        $landingFeature2Description = (string) $settings->get('landing.feature_2_description', 'Guide students through lessons with protected access and saved progress.');
        $landingFeature3Title = (string) $settings->get('landing.feature_3_title', 'Stronger instructor trust');
        $landingFeature3Description = (string) $settings->get('landing.feature_3_description', 'Show a real instructor, clear course cards, and a buying flow that feels premium.');
        $landingInstructorImagePath = $settings->get('landing.instructor_image');
        $landingInstructorImageUrl = MediaAsset::url($landingInstructorImagePath, MediaAsset::avatarFallbackPath($instructorName));
        $currentHeroImagePath = (string) $settings->get('hero.image', '');
        $heroImageUrl = MediaAsset::url($currentHeroImagePath, 'images/demo/real/hero-formal-2.jpg');
        $landingShowHero = (bool) $settings->get('landing.show_hero', true);
        $landingShowContactForm = (bool) $settings->get('landing.show_contact_form', true);
        $landingShowPlatformProof = (bool) $settings->get('landing.show_platform_proof', true);
        $landingShowAbout = (bool) $settings->get('landing.show_about', true);
        $landingShowCoursesPreview = (bool) $settings->get('landing.show_courses_preview', true);
        $landingShowProblemSection = (bool) $settings->get('landing.show_problem_section', true);
        $landingShowFlowSection = (bool) $settings->get('landing.show_flow_section', true);
        $landingShowTestimonials = (bool) $settings->get('landing.show_testimonials', true);
        $landingShowFaqSection = (bool) $settings->get('landing.show_faq_section', true);
        $landingShowFooterCta = (bool) $settings->get('landing.show_footer_cta', true);
        $landingHeroImageMode = (string) $settings->get('landing.hero_image_mode', 'cover');
        $landingHeroImageFocus = (string) $settings->get('landing.hero_image_focus', 'center');
        $socialTwitter = (string) $settings->get('instructor.social.twitter', '');
        $socialInstagram = (string) $settings->get('instructor.social.instagram', '');
        $socialYouTube = (string) $settings->get('instructor.social.youtube', '');
        $socialLinkedIn = (string) $settings->get('instructor.social.linkedin', '');
        $heroVideoUrl = (string) $settings->get('landing.hero_video_url', $socialYouTube);
        $landingCopy = LandingContent::copy($settings);
        $landingTestimonials = LandingContent::testimonials($settings);
        $landingFaqs = LandingContent::faqs($settings);

        $heroFontTitle = (int) $settings->get('hero.font.title', 56);
        $heroFontSubtitle = (int) $settings->get('hero.font.subtitle', 24);
        $heroFontDescription = (int) $settings->get('hero.font.description', 18);
        $heroImageWidth = (int) $settings->get('hero.image_width', 0);
        $heroImageHeight = (int) $settings->get('hero.image_height', 0);

        $googleLoginEnabled = (bool) $settings->get('auth.google.enabled', false);
        $googleClientId = (string) $settings->get('auth.google.client_id', '');
        $googleClientSecret = (string) $settings->get('auth.google.client_secret', '');

        $recaptchaEnabled = (bool) $settings->get('security.recaptcha.enabled', false);
        $recaptchaSiteKey = (string) $settings->get('security.recaptcha.site_key', (string) config('services.recaptcha.site_key', ''));
        $recaptchaSecretKey = (string) $settings->get('security.recaptcha.secret_key', (string) config('services.recaptcha.secret_key', ''));

        $whatsappEnabled = (bool) $settings->get('contact.whatsapp.enabled', false);
        $whatsappPhone = (string) $settings->get('contact.whatsapp.phone', '');
        $whatsappMessage = (string) $settings->get('contact.whatsapp.message', 'Hello! I have a question about your courses.');

        $legalTermsEn = (string) $settings->get('legal.terms.en', "1. Introduction\nBy using this site, you agree to these terms.\n\n2. User Accounts\nYou are responsible for your login credentials and agree not to misuse the platform.\n\n3. Course Access & Payments\nAccess to courses is granted upon valid payment or free enrollment as described.\n\n4. Refund Policy\nRefunds may be offered according to the instructor’s policy stated on the course page, subject to reasonable use.\n\n5. Intellectual Property\nAll learning materials are licensed for personal use only and may not be redistributed or shared.\n\n6. Termination\nWe may suspend or terminate access in cases of misuse or violation of these terms.\n\n7. Contact Information\nYou can reach us using the contact form on the site.");
        $legalPrivacyEn = (string) $settings->get('legal.privacy.en', "1. Information We Collect\nWe collect basic account details, payment data when required, and usage data to improve the service.\n\n2. How We Use Information\nWe use data to provide the service, enhance the experience, ensure security, and communicate updates.\n\n3. Cookies\nWe use cookies to remember preferences and analyze usage. You can disable cookies in your browser settings.\n\n4. Third-Party Services\nWe may use payment providers, analytics, and video hosting. Your data is subject to their policies.\n\n5. Data Security\nWe take reasonable measures to protect data without guaranteeing absolute security.\n\n6. User Rights\nYou may request to update or delete your data, subject to applicable law.\n\n7. Contact\nPlease use the site’s contact form to reach us.");

        $stripePublishableKey = (string) $settings->get('stripe.publishable_key', '');
        $stripeSecretKey = (string) $settings->get('stripe.secret_key', '');
        $stripeMode = (string) $settings->get('stripe.mode', 'test');
        $stripeHasSecret = (string) $settings->get('stripe.secret_key', '') !== '';
        $stripeStatusLabel = 'Disabled';
        $stripeStatusVariant = 'gray';
        $stripeStatusMessage = null;
        $stripeWebhookSecretExists = (string) $settings->get('stripe.webhook_secret', '') !== '';
        $stripePublishableKeyMasked = $stripePublishableKey !== '' && strlen($stripePublishableKey) > 12
            ? substr($stripePublishableKey, 0, 8).'…'.substr($stripePublishableKey, -4)
            : $stripePublishableKey;
        if ($paymentsStripeEnabled) {
            $pk = (string) config('services.stripe.publishable_key', '');
            $sk = (string) config('services.stripe.secret', '');
            $wh = (string) config('services.stripe.webhook_secret', '');
            $stripeResult = ['valid' => false, 'message' => null];
            if (! app()->environment(['testing', 'dusk', 'dusk.local'])) {
                $stripeResult = $stripeValidator->execute($pk, $sk, $wh);
            } else {
                $stripeResult['valid'] = ($pk !== '' && $sk !== '');
            }
            if ($stripeResult['valid']) {
                $stripeStatusLabel = 'Connected';
                $stripeStatusVariant = 'green';
            } else {
                $stripeStatusLabel = 'Needs attention';
                $stripeStatusVariant = 'red';
                $stripeStatusMessage = 'Stripe isn’t fully connected. Please review your keys and webhook.';
            }
        }

        $paypalClientId = (string) $settings->get('paypal.client_id', '');
        $paypalClientSecret = (string) $settings->get('paypal.client_secret', '');
        $paypalHasSecret = $paypalClientSecret !== '';
        $paypalMode = (string) $settings->get('paypal.mode', 'sandbox');
        $paypalWebhookSecretExists = (string) $settings->get('paypal.webhook_secret', '') !== '';
        $paypalStatusLabel = 'Disabled';
        $paypalStatusVariant = 'gray';
        $paypalStatusMessage = null;
        if ($paymentsPaypalEnabled) {
            $paypalResult = $paypalValidator->execute($paypalClientId, $paypalClientSecret, $paypalMode);
            if ($paypalResult['valid']) {
                $paypalStatusLabel = 'Connected';
                $paypalStatusVariant = 'green';
            } else {
                $paypalStatusLabel = 'Needs attention';
                $paypalStatusVariant = 'red';
                $paypalStatusMessage = 'PayPal isn’t fully connected. Please add your Client ID and Secret.';
            }
        }

        return view('dashboard.settings.edit', compact(
            'defaultLanguage',
            'defaultTheme',
            'demoEnabled',
            'logoUrl',
            'paymentsStripeEnabled',
            'paymentsPaypalEnabled',
            'paymentsManualInstructions',
            'instructorName',
            'landingHeroTitle',
            'landingHeroSubtitle',
            'landingHeroTitleEn',
            'landingHeroSubtitleEn',
            'heroTitleEn',
            'heroSubtitleEn',
            'landingFeature1Title',
            'landingFeature1Description',
            'landingFeature2Title',
            'landingFeature2Description',
            'landingFeature3Title',
            'landingFeature3Description',
            'landingInstructorImageUrl',
            'landingShowHero',
            'landingShowContactForm',
            'landingShowPlatformProof',
            'landingShowAbout',
            'landingShowCoursesPreview',
            'landingShowProblemSection',
            'landingShowFlowSection',
            'landingShowTestimonials',
            'landingShowFaqSection',
            'landingShowFooterCta',
            'landingHeroImageMode',
            'landingHeroImageFocus',
            'socialTwitter',
            'socialInstagram',
            'socialYouTube',
            'socialLinkedIn',
            'heroVideoUrl',
            'landingCopy',
            'landingTestimonials',
            'landingFaqs',
            'legalTermsEn',
            'legalPrivacyEn',
            'googleLoginEnabled',
            'googleClientId',
            'googleClientSecret',
            'recaptchaEnabled',
            'recaptchaSiteKey',
            'recaptchaSecretKey',
            'whatsappEnabled',
            'whatsappPhone',
            'whatsappMessage',
            'securityRightClickEnabled',
            'stripeStatusLabel',
            'stripeStatusVariant',
            'stripeStatusMessage',
            'stripePublishableKey',
            'stripeSecretKey',
            'stripePublishableKeyMasked',
            'stripeMode',
            'stripeHasSecret',
            'stripeWebhookSecretExists',
            'paypalClientId',
            'paypalClientSecret',
            'paypalHasSecret',
            'paypalMode',
            'paypalStatusLabel',
            'paypalStatusVariant',
            'paypalStatusMessage',
            'paypalWebhookSecretExists',
            'heroImageUrl',
            'heroFontTitle',
            'heroFontSubtitle',
            'heroFontDescription',
            'heroImageWidth',
            'heroImageHeight',
        ));
    }

    public function update(Request $request, SettingsService $settings, ValidateStripeConfigAction $stripeValidator, ValidatePayPalConfigAction $paypalValidator): RedirectResponse
    {
        $group = (string) $request->input('settings_group', '');

        if ($group === 'general') {
            $validated = $request->validate([
                'logo' => ['nullable', 'image', 'max:2048'],
                'default_theme' => ['required', 'in:light,dark,system'],
                'demo_enabled' => ['nullable', 'boolean'],
                'primary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'secondary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'accent' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            ]);
        } elseif ($group === 'payments') {
            $validated = $request->validate([
                'payments_stripe_enabled' => ['nullable', 'boolean'],
                'payments_paypal_enabled' => ['nullable', 'boolean'],
                'payments_manual_instructions' => ['nullable', 'string'],
                'stripe_publishable_key' => ['nullable', 'string'],
                'stripe_secret_key' => ['nullable', 'string'],
                'stripe_mode' => ['nullable', 'in:test,live'],
                'stripe_webhook_secret' => ['nullable', 'string'],
                'paypal_client_id' => ['nullable', 'string'],
                'paypal_client_secret' => ['nullable', 'string'],
                'paypal_mode' => ['nullable', 'in:sandbox,live'],
                'paypal_webhook_secret' => ['nullable', 'string'],
            ]);
        } elseif ($group === 'authentication') {
            $validated = $request->validate([
                'auth_google_enabled' => ['nullable', 'boolean'],
                'auth_google_client_id' => ['nullable', 'string'],
                'auth_google_client_secret' => ['nullable', 'string'],
            ]);
        } elseif ($group === 'security') {
            $validated = $request->validate([
                'security_recaptcha_enabled' => ['nullable', 'boolean'],
                'security_recaptcha_site_key' => ['nullable', 'string'],
                'security_recaptcha_secret_key' => ['nullable', 'string'],
                'security_right_click_enabled' => ['nullable', 'boolean'],
                'legal_terms_en' => ['nullable', 'string'],
                'legal_privacy_en' => ['nullable', 'string'],
            ]);
        } elseif ($group === 'notifications') {
            $validated = $request->validate([
                'contact_whatsapp_enabled' => ['nullable', 'boolean'],
                'contact_whatsapp_phone' => ['nullable', 'string', 'max:32'],
                'contact_whatsapp_message' => ['nullable', 'string', 'max:500'],
            ]);
        } elseif ($group === 'landing') {
            $validated = $request->validate([
                'instructor_name' => ['nullable', 'string', 'max:255'],
                'landing_hero_title' => ['nullable', 'string', 'max:255'],
                'landing_hero_subtitle' => ['nullable', 'string', 'max:255'],
                'landing_hero_title_en' => ['nullable', 'string', 'max:255'],
                'landing_hero_subtitle_en' => ['nullable', 'string', 'max:255'],
                'hero_title_en' => ['nullable', 'string', 'max:255'],
                'hero_subtitle_en' => ['nullable', 'string', 'max:255'],
                'hero_font_title' => ['nullable', 'integer', 'between:28,96'],
                'hero_font_subtitle' => ['nullable', 'integer', 'between:18,48'],
                'hero_font_description' => ['nullable', 'integer', 'between:14,28'],
                'landing_feature_1_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_1_description' => ['nullable', 'string'],
                'landing_feature_2_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_2_description' => ['nullable', 'string'],
                'landing_feature_3_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_3_description' => ['nullable', 'string'],
                'landing_instructor_image' => ['nullable', 'image', 'max:2048'],
                'landing_show_hero' => ['nullable', 'boolean'],
                'landing_show_contact_form' => ['nullable', 'boolean'],
                'landing_show_platform_proof' => ['nullable', 'boolean'],
                'landing_show_about' => ['nullable', 'boolean'],
                'landing_show_courses_preview' => ['nullable', 'boolean'],
                'landing_show_problem_section' => ['nullable', 'boolean'],
                'landing_show_flow_section' => ['nullable', 'boolean'],
                'landing_show_testimonials' => ['nullable', 'boolean'],
                'landing_show_faq_section' => ['nullable', 'boolean'],
                'landing_show_footer_cta' => ['nullable', 'boolean'],
                'landing_hero_image_mode' => ['nullable', 'in:contain,cover'],
                'landing_hero_image_focus' => ['nullable', 'in:center,top,bottom,left,right'],
                'hero_image_width' => ['nullable', 'integer', 'between:100,3000'],
                'hero_image_height' => ['nullable', 'integer', 'between:100,2000'],
                'social_twitter' => ['nullable', 'url'],
                'social_instagram' => ['nullable', 'url'],
                'social_youtube' => ['nullable', 'url'],
                'social_linkedin' => ['nullable', 'url'],
                'hero_video_url' => ['nullable', 'url'],
                'landing_copy' => ['nullable', 'array'],
                'landing_copy.*' => ['nullable', 'string', 'max:2000'],
                'landing_testimonials' => ['nullable', 'array'],
                'landing_testimonials.*.name' => ['nullable', 'string', 'max:255'],
                'landing_testimonials.*.role' => ['nullable', 'string', 'max:255'],
                'landing_testimonials.*.avatar' => ['nullable', 'string', 'max:500'],
                'landing_testimonials.*.quote' => ['nullable', 'string', 'max:1000'],
                'landing_faqs' => ['nullable', 'array'],
                'landing_faqs.*.question' => ['nullable', 'string', 'max:255'],
                'landing_faqs.*.answer' => ['nullable', 'string', 'max:1000'],
                'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'remove_hero_image' => ['nullable', 'boolean'],
            ]);
        } else {
            $validated = $request->validate([
                'logo' => ['nullable', 'image', 'max:2048'],
                'default_theme' => ['nullable', 'in:light,dark,system'],
                'demo_enabled' => ['nullable', 'boolean'],
                'primary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'secondary' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'accent' => ['nullable', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
                'payments_stripe_enabled' => ['nullable', 'boolean'],
                'payments_paypal_enabled' => ['nullable', 'boolean'],
                'payments_manual_instructions' => ['nullable', 'string'],
                'instructor_name' => ['nullable', 'string', 'max:255'],
                'landing_hero_title' => ['nullable', 'string', 'max:255'],
                'landing_hero_subtitle' => ['nullable', 'string', 'max:255'],
                'landing_hero_title_en' => ['nullable', 'string', 'max:255'],
                'landing_hero_subtitle_en' => ['nullable', 'string', 'max:255'],
                'hero_title_en' => ['nullable', 'string', 'max:255'],
                'hero_subtitle_en' => ['nullable', 'string', 'max:255'],
                'landing_feature_1_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_1_description' => ['nullable', 'string'],
                'landing_feature_2_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_2_description' => ['nullable', 'string'],
                'landing_feature_3_title' => ['nullable', 'string', 'max:255'],
                'landing_feature_3_description' => ['nullable', 'string'],
                'landing_instructor_image' => ['nullable', 'image', 'max:2048'],
                'landing_show_hero' => ['nullable', 'boolean'],
                'landing_show_contact_form' => ['nullable', 'boolean'],
                'landing_show_platform_proof' => ['nullable', 'boolean'],
                'landing_show_about' => ['nullable', 'boolean'],
                'landing_show_courses_preview' => ['nullable', 'boolean'],
                'landing_show_problem_section' => ['nullable', 'boolean'],
                'landing_show_flow_section' => ['nullable', 'boolean'],
                'landing_show_testimonials' => ['nullable', 'boolean'],
                'landing_show_faq_section' => ['nullable', 'boolean'],
                'landing_show_footer_cta' => ['nullable', 'boolean'],
                'landing_hero_image_mode' => ['nullable', 'in:contain,cover'],
                'landing_hero_image_focus' => ['nullable', 'in:center,top,bottom,left,right'],
                'social_twitter' => ['nullable', 'url'],
                'social_instagram' => ['nullable', 'url'],
                'social_youtube' => ['nullable', 'url'],
                'social_linkedin' => ['nullable', 'url'],
                'hero_video_url' => ['nullable', 'url'],
                'landing_copy' => ['nullable', 'array'],
                'landing_copy.*' => ['nullable', 'string', 'max:2000'],
                'landing_testimonials' => ['nullable', 'array'],
                'landing_testimonials.*.name' => ['nullable', 'string', 'max:255'],
                'landing_testimonials.*.role' => ['nullable', 'string', 'max:255'],
                'landing_testimonials.*.avatar' => ['nullable', 'string', 'max:500'],
                'landing_testimonials.*.quote' => ['nullable', 'string', 'max:1000'],
                'landing_faqs' => ['nullable', 'array'],
                'landing_faqs.*.question' => ['nullable', 'string', 'max:255'],
                'landing_faqs.*.answer' => ['nullable', 'string', 'max:1000'],
                'legal_terms_en' => ['nullable', 'string'],
                'legal_privacy_en' => ['nullable', 'string'],
                'auth_google_enabled' => ['nullable', 'boolean'],
                'auth_google_client_id' => ['nullable', 'string'],
                'auth_google_client_secret' => ['nullable', 'string'],
                'security_recaptcha_enabled' => ['nullable', 'boolean'],
                'security_recaptcha_site_key' => ['nullable', 'string'],
                'security_recaptcha_secret_key' => ['nullable', 'string'],
                'contact_whatsapp_enabled' => ['nullable', 'boolean'],
                'contact_whatsapp_phone' => ['nullable', 'string', 'max:32'],
                'contact_whatsapp_message' => ['nullable', 'string', 'max:500'],
                'stripe_publishable_key' => ['nullable', 'string'],
                'stripe_secret_key' => ['nullable', 'string'],
                'stripe_mode' => ['nullable', 'in:test,live'],
                'stripe_webhook_secret' => ['nullable', 'string'],
                'paypal_client_id' => ['nullable', 'string'],
                'paypal_client_secret' => ['nullable', 'string'],
                'paypal_mode' => ['nullable', 'in:sandbox,live'],
            ]);
        }

        $stripeEnabled = $request->boolean('payments_stripe_enabled');
        if (($group === 'payments' || $group === '') && $stripeEnabled && ! app()->environment(['testing', 'dusk', 'dusk.local'])) {
            $publishableKeyInput = (string) ($validated['stripe_publishable_key'] ?? '');
            $secretKeyInput = (string) ($validated['stripe_secret_key'] ?? '');
            if (! str_starts_with($publishableKeyInput, 'pk_') || ! str_starts_with($secretKeyInput, 'sk_')) {
                return back()->withErrors(['stripe' => __('Publishable key must start with "pk_" and secret key must start with "sk_".')])->withInput();
            }
        }

        $paypalEnabled = $request->boolean('payments_paypal_enabled');
        if (($group === 'payments' || $group === '') && $paypalEnabled) {
            $paypalClientIdInput = (string) ($validated['paypal_client_id'] ?? '');
            $paypalSecretInput = (string) ($validated['paypal_client_secret'] ?? '');
            if ($paypalClientIdInput === '' || $paypalSecretInput === '') {
                return back()->withErrors(['paypal' => __('PayPal Client ID and Client Secret are required when PayPal is enabled.')])->withInput();
            }
        }
        if (($group === 'payments' || $group === '') && $paypalEnabled && ! app()->environment(['testing', 'dusk', 'dusk.local'])) {
            $paypalModeInput = (string) ($validated['paypal_mode'] ?? (string) $settings->get('paypal.mode', 'sandbox'));
            $paypalClientIdInput = (string) ($validated['paypal_client_id'] ?? (string) $settings->get('paypal.client_id', ''));
            $paypalSecretInput = (string) ($validated['paypal_client_secret'] ?? (string) $settings->get('paypal.client_secret', ''));
            $result = $paypalValidator->execute($paypalClientIdInput, $paypalSecretInput, $paypalModeInput);
            if (! ($result['valid'] ?? false)) {
                return back()->withErrors(['paypal' => (string) ($result['message'] ?? 'PayPal configuration is invalid.')])->withInput();
            }
        }

        $googleEnabled = $request->boolean('auth_google_enabled');
        if (($group === 'authentication' || $group === '') && $googleEnabled) {
            $googleId = (string) ($validated['auth_google_client_id'] ?? '');
            $googleSecret = (string) ($validated['auth_google_client_secret'] ?? '');
            if ($googleId === '' || $googleSecret === '') {
                return back()->withErrors(['auth_google' => __('Google Client ID and Secret are required when Google login is enabled.')])->withInput();
            }
        }

        $recaptchaToggle = $request->boolean('security_recaptcha_enabled');
        if (($group === 'security' || $group === '') && $recaptchaToggle) {
            $siteKey = (string) ($validated['security_recaptcha_site_key'] ?? '');
            $secretKey = (string) ($validated['security_recaptcha_secret_key'] ?? '');
            if ($siteKey === '' || $secretKey === '') {
                return back()->withErrors(['security_recaptcha' => __('reCAPTCHA Site Key and Secret Key are required when reCAPTCHA is enabled.')])->withInput();
            }
        }

        $values = [];
        if ($group === 'general' || $group === '') {
            $values = array_merge($values, [
                'site.default_language' => 'en',
                'ui.theme.default' => ($validated['default_theme'] ?? $settings->get('ui.theme.default', 'system')),
                'demo.enabled' => $request->has('demo_enabled')
                    ? $request->boolean('demo_enabled')
                    : filter_var($settings->get('demo.enabled', config('demo.enabled')), FILTER_VALIDATE_BOOL),
                'theme.primary' => $validated['primary'] ?? (string) $settings->get('theme.primary', '#F5B800'),
                'theme.secondary' => $validated['secondary'] ?? (string) $settings->get('theme.secondary', '#0B0B0B'),
                'theme.accent' => $validated['accent'] ?? (string) $settings->get('theme.accent', '#F7F7F7'),
            ]);
        }
        if ($group === 'payments' || $group === '') {
            $values = array_merge($values, [
                'payments.stripe.enabled' => $stripeEnabled,
                'stripe.enabled' => $stripeEnabled,
                'payments.paypal.enabled' => $paypalEnabled,
                'paypal.enabled' => $paypalEnabled,
                'payments.manual.instructions' => $validated['payments_manual_instructions'] ?? (string) $settings->get('payments.manual.instructions', ''),
            ]);
        }
        if ($group === 'landing' || $group === '') {
            $values = array_merge($values, [
                'instructor.name' => $validated['instructor_name'] ?? (string) $settings->get('instructor.name', ''),
                // Legacy fields retained for backward compatibility (input only)
                'landing.hero_title' => $validated['landing_hero_title'] ?? (string) $settings->get('landing.hero_title', ''),
                'landing.hero_subtitle' => $validated['landing_hero_subtitle'] ?? (string) $settings->get('landing.hero_subtitle', ''),
                'landing.hero_title_en' => $validated['landing_hero_title_en'] ?? (string) $settings->get('landing.hero_title_en', ''),
                'landing.hero_subtitle_en' => $validated['landing_hero_subtitle_en'] ?? (string) $settings->get('landing.hero_subtitle_en', ''),
                // New single source of truth
                'hero.title.en' => $validated['hero_title_en'] ?? (string) $settings->get('hero.title.en', ''),
                'hero.subtitle.en' => $validated['hero_subtitle_en'] ?? (string) $settings->get('hero.subtitle.en', ''),
                'landing.feature_1_title' => $validated['landing_feature_1_title'] ?? (string) $settings->get('landing.feature_1_title', ''),
                'landing.feature_1_description' => $validated['landing_feature_1_description'] ?? (string) $settings->get('landing.feature_1_description', ''),
                'landing.feature_2_title' => $validated['landing_feature_2_title'] ?? (string) $settings->get('landing.feature_2_title', ''),
                'landing.feature_2_description' => $validated['landing_feature_2_description'] ?? (string) $settings->get('landing.feature_2_description', ''),
                'landing.feature_3_title' => $validated['landing_feature_3_title'] ?? (string) $settings->get('landing.feature_3_title', ''),
                'landing.feature_3_description' => $validated['landing_feature_3_description'] ?? (string) $settings->get('landing.feature_3_description', ''),
                'landing.show_hero' => $request->has('landing_show_hero')
                    ? $request->boolean('landing_show_hero')
                    : (bool) $settings->get('landing.show_hero', true),
                'landing.show_contact_form' => $request->has('landing_show_contact_form')
                    ? $request->boolean('landing_show_contact_form')
                    : (bool) $settings->get('landing.show_contact_form', true),
                'landing.show_platform_proof' => $request->has('landing_show_platform_proof')
                    ? $request->boolean('landing_show_platform_proof')
                    : (bool) $settings->get('landing.show_platform_proof', true),
                'landing.show_about' => $request->has('landing_show_about')
                    ? $request->boolean('landing_show_about')
                    : (bool) $settings->get('landing.show_about', true),
                'landing.show_courses_preview' => $request->has('landing_show_courses_preview')
                    ? $request->boolean('landing_show_courses_preview')
                    : (bool) $settings->get('landing.show_courses_preview', true),
                'landing.show_problem_section' => $request->has('landing_show_problem_section')
                    ? $request->boolean('landing_show_problem_section')
                    : (bool) $settings->get('landing.show_problem_section', true),
                'landing.show_flow_section' => $request->has('landing_show_flow_section')
                    ? $request->boolean('landing_show_flow_section')
                    : (bool) $settings->get('landing.show_flow_section', true),
                'landing.show_testimonials' => $request->has('landing_show_testimonials')
                    ? $request->boolean('landing_show_testimonials')
                    : (bool) $settings->get('landing.show_testimonials', true),
                'landing.show_faq_section' => $request->has('landing_show_faq_section')
                    ? $request->boolean('landing_show_faq_section')
                    : (bool) $settings->get('landing.show_faq_section', true),
                'landing.show_footer_cta' => $request->has('landing_show_footer_cta')
                    ? $request->boolean('landing_show_footer_cta')
                    : (bool) $settings->get('landing.show_footer_cta', true),
                'landing.hero_image_mode' => $validated['landing_hero_image_mode'] ?? (string) $settings->get('landing.hero_image_mode', 'cover'),
                'landing.hero_image_focus' => $validated['landing_hero_image_focus'] ?? (string) $settings->get('landing.hero_image_focus', 'center'),
                'instructor.social.twitter' => $validated['social_twitter'] ?? (string) $settings->get('instructor.social.twitter', ''),
                'instructor.social.instagram' => $validated['social_instagram'] ?? (string) $settings->get('instructor.social.instagram', ''),
                'instructor.social.youtube' => $validated['social_youtube'] ?? (string) $settings->get('instructor.social.youtube', ''),
                'instructor.social.linkedin' => $validated['social_linkedin'] ?? (string) $settings->get('instructor.social.linkedin', ''),
                'landing.hero_video_url' => $validated['hero_video_url'] ?? (string) $settings->get('landing.hero_video_url', ''),
            ]);
            if (array_key_exists('hero_image_width', $validated)) {
                $values['hero.image_width'] = (int) $validated['hero_image_width'];
            }
            if (array_key_exists('hero_image_height', $validated)) {
                $values['hero.image_height'] = (int) $validated['hero_image_height'];
            }
            if (array_key_exists('hero_font_title', $validated)) {
                $values['hero.font.title'] = (int) $validated['hero_font_title'];
            }
            if (array_key_exists('hero_font_subtitle', $validated)) {
                $values['hero.font.subtitle'] = (int) $validated['hero_font_subtitle'];
            }
            if (array_key_exists('hero_font_description', $validated)) {
                $values['hero.font.description'] = (int) $validated['hero_font_description'];
            }
            // Migrate legacy hero text inputs to the new keys
            $values['hero.title.en'] = (string) (
                $validated['hero_title_en']
                ?? $validated['landing_hero_title_en']
                ?? $validated['landing_hero_title']
                ?? (string) $settings->get('hero.title.en', (string) $settings->get('landing.hero_title_en', (string) $settings->get('landing.hero_title', '')))
            );
            $values['hero.subtitle.en'] = (string) (
                $validated['hero_subtitle_en']
                ?? $validated['landing_hero_subtitle_en']
                ?? $validated['landing_hero_subtitle']
                ?? (string) $settings->get('hero.subtitle.en', (string) $settings->get('landing.hero_subtitle_en', (string) $settings->get('landing.hero_subtitle', '')))
            );
            // Clean up legacy duplicates
            $values['landing.hero_title'] = null;
            $values['landing.hero_subtitle'] = null;
            $values['landing.hero_title_en'] = null;
            $values['landing.hero_subtitle_en'] = null;

            foreach (array_keys(LandingContent::COPY_DEFAULTS) as $copyKey) {
                $values["landing.copy.{$copyKey}"] = (string) data_get($validated, "landing_copy.{$copyKey}", $settings->get("landing.copy.{$copyKey}", LandingContent::COPY_DEFAULTS[$copyKey]));
            }

            foreach (LandingContent::TESTIMONIAL_DEFAULTS as $index => $testimonialDefaults) {
                $number = $index + 1;
                $values["landing.testimonials.{$number}.name"] = (string) data_get($validated, "landing_testimonials.{$index}.name", $settings->get("landing.testimonials.{$number}.name", $testimonialDefaults['name']));
                $values["landing.testimonials.{$number}.role"] = (string) data_get($validated, "landing_testimonials.{$index}.role", $settings->get("landing.testimonials.{$number}.role", $testimonialDefaults['role']));
                $values["landing.testimonials.{$number}.avatar"] = (string) data_get($validated, "landing_testimonials.{$index}.avatar", $settings->get("landing.testimonials.{$number}.avatar", $testimonialDefaults['avatar']));
                $values["landing.testimonials.{$number}.quote"] = (string) data_get($validated, "landing_testimonials.{$index}.quote", $settings->get("landing.testimonials.{$number}.quote", $testimonialDefaults['quote']));
            }

            foreach (LandingContent::FAQ_DEFAULTS as $index => $faqDefaults) {
                $number = $index + 1;
                $values["landing.faqs.{$number}.question"] = (string) data_get($validated, "landing_faqs.{$index}.question", $settings->get("landing.faqs.{$number}.question", $faqDefaults['question']));
                $values["landing.faqs.{$number}.answer"] = (string) data_get($validated, "landing_faqs.{$index}.answer", $settings->get("landing.faqs.{$number}.answer", $faqDefaults['answer']));
            }
        }
        if ($group === 'authentication' || $group === '') {
            $values = array_merge($values, [
                'auth.google.enabled' => $googleEnabled,
                'auth.google.client_id' => (string) ($validated['auth_google_client_id'] ?? (string) $settings->get('auth.google.client_id', '')),
                'auth.google.client_secret' => (string) ($validated['auth_google_client_secret'] ?? (string) $settings->get('auth.google.client_secret', '')),
            ]);
        }
        if ($group === 'security' || $group === '') {
            $values = array_merge($values, [
                'security.recaptcha.enabled' => $recaptchaToggle,
                'security.recaptcha.site_key' => (string) ($validated['security_recaptcha_site_key'] ?? (string) $settings->get('security.recaptcha.site_key', '')),
                'security.recaptcha.secret_key' => (string) ($validated['security_recaptcha_secret_key'] ?? (string) $settings->get('security.recaptcha.secret_key', '')),
                'security.right_click.enabled' => $request->has('security_right_click_enabled')
                    ? $request->boolean('security_right_click_enabled')
                    : (bool) $settings->get('security.right_click.enabled', true),
                'legal.terms.en' => $validated['legal_terms_en'] ?? (string) $settings->get('legal.terms.en', ''),
                'legal.privacy.en' => $validated['legal_privacy_en'] ?? (string) $settings->get('legal.privacy.en', ''),
            ]);
        }
        if ($group === 'notifications' || $group === '') {
            $values = array_merge($values, [
                'contact.whatsapp.enabled' => $request->boolean('contact_whatsapp_enabled'),
                'contact.whatsapp.phone' => (string) ($validated['contact_whatsapp_phone'] ?? (string) $settings->get('contact.whatsapp.phone', '')),
                'contact.whatsapp.message' => (string) ($validated['contact_whatsapp_message'] ?? (string) $settings->get('contact.whatsapp.message', '')),
            ]);
        }
        if (array_key_exists('stripe_mode', $validated)) {
            $values['stripe.mode'] = (string) $validated['stripe_mode'] ?? 'test';
        }
        if (array_key_exists('stripe_publishable_key', $validated)) {
            $values['stripe.publishable_key'] = (string) $validated['stripe_publishable_key'];
        }
        if (array_key_exists('stripe_secret_key', $validated) && trim((string) $validated['stripe_secret_key']) !== '') {
            $values['stripe.secret_key'] = (string) $validated['stripe_secret_key'];
        }
        if (array_key_exists('stripe_webhook_secret', $validated)) {
            $whsec = trim((string) $validated['stripe_webhook_secret']);
            if ($whsec !== '' && str_starts_with($whsec, 'whsec_')) {
                $values['stripe.webhook_secret'] = $whsec;
            }
        }
        if (array_key_exists('paypal_mode', $validated)) {
            $values['paypal.mode'] = (string) $validated['paypal_mode'] ?? 'sandbox';
        }
        if (array_key_exists('paypal_client_id', $validated)) {
            $values['paypal.client_id'] = (string) $validated['paypal_client_id'];
        }
        if (array_key_exists('paypal_client_secret', $validated) && trim((string) $validated['paypal_client_secret']) !== '') {
            $values['paypal.client_secret'] = (string) $validated['paypal_client_secret'];
        }
        if (array_key_exists('paypal_webhook_secret', $validated)) {
            $whsec = trim((string) $validated['paypal_webhook_secret']);
            if ($whsec !== '') {
                $values['paypal.webhook_secret'] = $whsec;
            }
        }
        if (array_key_exists('legal_terms_en', $validated)) {
            $values['legal.terms.en'] = $validated['legal_terms_en'];
        }
        if (array_key_exists('legal_privacy_en', $validated)) {
            $values['legal.privacy.en'] = $validated['legal_privacy_en'];
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $values['site.logo_path'] = $path;
        }

        if ($request->hasFile('landing_instructor_image')) {
            $path = $request->file('landing_instructor_image')->store('landing', 'public');
            $values['landing.instructor_image'] = $path;
        }
        if ($group === 'landing') {
            $removeHero = $request->boolean('remove_hero_image');
            if ($removeHero) {
                $current = (string) $settings->get('hero.image', '');
                if ($current !== '') {
                    Storage::disk('public')->delete($current);
                }
                Setting::query()->where('key', 'hero.image')->delete();
            } elseif ($request->hasFile('hero_image')) {
                $current = (string) $settings->get('hero.image', '');
                $path = $request->file('hero_image')->store('hero', 'public');
                if ($current !== '' && $current !== $path) {
                    Storage::disk('public')->delete($current);
                }
                $values['hero.image'] = $path;
            }
        }

        $settings->set($values);

        Setting::query()->whereIn('key', [
            'typography.arabic_font',
            'landing.hero_title_ar',
            'landing.hero_subtitle_ar',
            'hero.title.ar',
            'hero.subtitle.ar',
            'legal.terms.ar',
            'legal.privacy.ar',
        ])->delete();

        if (($group === 'payments' || $group === '') && $paypalEnabled) {
            return back()->with('status', 'PayPal is connected successfully.');
        }

        if (($group === 'payments' || $group === '') && $stripeEnabled) {
            return back()->with('status', 'Stripe is connected successfully.');
        }

        return back()->with('status', 'Settings updated.');
    }
}
