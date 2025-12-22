<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Actions\Payments\ValidateStripeConfigAction;
use App\Actions\Payments\ValidatePayPalConfigAction;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(SettingsService $settings, ValidateStripeConfigAction $stripeValidator, ValidatePayPalConfigAction $paypalValidator): View
    {
        $defaultLanguage = $settings->get('site.default_language', 'en');
        $logoPath = $settings->get('site.logo_path');

        $paymentsStripeEnabled = (bool) $settings->get('payments.stripe.enabled', true);
        $paymentsPaypalEnabled = (bool) $settings->get('payments.paypal.enabled', true);
        $paymentsManualInstructions = (string) $settings->get('payments.manual.instructions', 'Send the course fee via bank transfer or cash and upload your proof of payment.');

        $logoUrl = $logoPath ? asset('storage/'.$logoPath) : null;

        $instructorName = (string) $settings->get('instructor.name', '');
        $landingHeroTitle = (string) $settings->get('landing.hero_title', 'Teach and sell your courses with CourseFlow');
        $landingHeroSubtitle = (string) $settings->get('landing.hero_subtitle', 'Launch a clean, modern course platform in minutes.');
        $landingHeroTitleEn = (string) $settings->get('landing.hero_title_en', '');
        $landingHeroTitleAr = (string) $settings->get('landing.hero_title_ar', '');
        $landingHeroSubtitleEn = (string) $settings->get('landing.hero_subtitle_en', '');
        $landingHeroSubtitleAr = (string) $settings->get('landing.hero_subtitle_ar', '');
        $landingFeature1Title = (string) $settings->get('landing.feature_1_title', 'Launch quickly');
        $landingFeature1Description = (string) $settings->get('landing.feature_1_description', 'Ship a polished learning platform without building everything from scratch.');
        $landingFeature2Title = (string) $settings->get('landing.feature_2_title', 'Sell courses with confidence');
        $landingFeature2Description = (string) $settings->get('landing.feature_2_description', 'Stripe, PayPal and manual payments are ready for production.');
        $landingFeature3Title = (string) $settings->get('landing.feature_3_title', 'Delight your students');
        $landingFeature3Description = (string) $settings->get('landing.feature_3_description', 'Clean lessons, progress tracking and RTL-ready layouts out of the box.');
        $landingInstructorImagePath = $settings->get('landing.instructor_image');
        $landingInstructorImageUrl = $landingInstructorImagePath ? asset('storage/'.$landingInstructorImagePath) : null;
        $landingShowHero = (bool) $settings->get('landing.show_hero', true);
        $landingShowContactForm = (bool) $settings->get('landing.show_contact_form', false);
        $landingShowAbout = (bool) $settings->get('landing.show_about', true);
        $landingShowCoursesPreview = (bool) $settings->get('landing.show_courses_preview', true);
        $landingShowTestimonials = (bool) $settings->get('landing.show_testimonials', true);
        $landingShowFooterCta = (bool) $settings->get('landing.show_footer_cta', true);
        $landingHeroImageMode = (string) $settings->get('landing.hero_image_mode', 'contain');
        $landingHeroImageFocus = (string) $settings->get('landing.hero_image_focus', 'center');
        $socialTwitter = (string) $settings->get('instructor.social.twitter', '');
        $socialInstagram = (string) $settings->get('instructor.social.instagram', '');
        $socialYouTube = (string) $settings->get('instructor.social.youtube', '');
        $socialLinkedIn = (string) $settings->get('instructor.social.linkedin', '');

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
        $legalTermsAr = (string) $settings->get('legal.terms.ar', "1. المقدمة\nباستخدام هذا الموقع، فإنك توافق على هذه الشروط.\n\n2. حسابات المستخدمين\nأنت مسؤول عن الحفاظ على سرية بيانات الدخول وعدم إساءة الاستخدام.\n\n3. الوصول إلى الدورات والمدفوعات\nيتم منح الوصول إلى الدورات عند إتمام الدفع أو التسجيل المجاني وفقاً للوصف.\n\n4. سياسة الاسترداد\nقد يتم تقديم استرداد وفق سياسة المعلم المنصوص عليها في صفحة الدورة، مع مراعاة الاستخدام المعقول.\n\n5. الملكية الفكرية\nجميع المواد التعليمية مرخصة للاستخدام الشخصي فقط ولا يجوز إعادة توزيعها أو مشاركتها.\n\n6. الإنهاء\nيجوز لنا تعليق أو إنهاء الوصول عند إساءة الاستخدام أو مخالفة الشروط.\n\n7. معلومات الاتصال\nيمكنك التواصل عبر نموذج الاتصال داخل الموقع.");
        $legalPrivacyEn = (string) $settings->get('legal.privacy.en', "1. Information We Collect\nWe collect basic account details, payment data when required, and usage data to improve the service.\n\n2. How We Use Information\nWe use data to provide the service, enhance the experience, ensure security, and communicate updates.\n\n3. Cookies\nWe use cookies to remember preferences and analyze usage. You can disable cookies in your browser settings.\n\n4. Third-Party Services\nWe may use payment providers, analytics, and video hosting. Your data is subject to their policies.\n\n5. Data Security\nWe take reasonable measures to protect data without guaranteeing absolute security.\n\n6. User Rights\nYou may request to update or delete your data, subject to applicable law.\n\n7. Contact\nPlease use the site’s contact form to reach us.");
        $legalPrivacyAr = (string) $settings->get('legal.privacy.ar', "1. المعلومات التي نجمعها\nنقوم بجمع معلومات الحساب الأساسية، بيانات الدفع عند الحاجة، وبيانات الاستخدام لتحسين الخدمة.\n\n2. كيفية استخدام المعلومات\nنستخدم البيانات لتقديم الخدمة، تحسين التجربة، وضمان الأمان وإبلاغك بالتحديثات.\n\n3. ملفات تعريف الارتباط\nنستخدم ملفات تعريف الارتباط لتذكر تفضيلاتك وتحليل الاستخدام. يمكنك تعطيلها من إعدادات المتصفح.\n\n4. الخدمات الخارجية\nقد نستخدم موفري الدفع والتحليلات وخدمات استضافة الفيديو. تخضع بياناتك لسياسات هذه الخدمات.\n\n5. أمان البيانات\nنتخذ تدابير معقولة لحماية البيانات، دون ضمان حماية مطلقة.\n\n6. حقوق المستخدم\nيمكنك طلب تحديث أو حذف بياناتك وفقاً للقانون المعمول به.\n\n7. الاتصال\nيرجى استخدام نموذج الاتصال داخل الموقع للتواصل.");

        $stripeStatusLabel = 'Disabled';
        $stripeStatusVariant = 'gray';
        $stripeStatusMessage = null;
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

        $paypalStatusLabel = 'Disabled';
        $paypalStatusVariant = 'gray';
        $paypalStatusMessage = null;
        if ($paymentsPaypalEnabled) {
            $clientId = (string) config('services.paypal.client_id', '');
            $clientSecret = (string) config('services.paypal.client_secret', '');
            $baseUrl = (string) config('services.paypal.base_url', '');
            $mode = str_contains(strtolower($baseUrl), 'sandbox') ? 'sandbox' : 'live';
            $paypalResult = $paypalValidator->execute($clientId, $clientSecret, $mode);
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
            'logoUrl',
            'paymentsStripeEnabled',
            'paymentsPaypalEnabled',
            'paymentsManualInstructions',
            'instructorName',
            'landingHeroTitle',
            'landingHeroSubtitle',
            'landingHeroTitleEn',
            'landingHeroTitleAr',
            'landingHeroSubtitleEn',
            'landingHeroSubtitleAr',
            'landingFeature1Title',
            'landingFeature1Description',
            'landingFeature2Title',
            'landingFeature2Description',
            'landingFeature3Title',
            'landingFeature3Description',
            'landingInstructorImageUrl',
            'landingShowHero',
            'landingShowContactForm',
            'landingShowAbout',
            'landingShowCoursesPreview',
            'landingShowTestimonials',
            'landingShowFooterCta',
            'landingHeroImageMode',
            'landingHeroImageFocus',
            'socialTwitter',
            'socialInstagram',
            'socialYouTube',
            'socialLinkedIn',
            'legalTermsEn',
            'legalTermsAr',
            'legalPrivacyEn',
            'legalPrivacyAr',
            'googleLoginEnabled',
            'googleClientId',
            'googleClientSecret',
            'recaptchaEnabled',
            'recaptchaSiteKey',
            'recaptchaSecretKey',
            'whatsappEnabled',
            'whatsappPhone',
            'whatsappMessage',
            'stripeStatusLabel',
            'stripeStatusVariant',
            'stripeStatusMessage',
            'paypalStatusLabel',
            'paypalStatusVariant',
            'paypalStatusMessage',
        ));
    }

    public function update(Request $request, SettingsService $settings, ValidateStripeConfigAction $stripeValidator, ValidatePayPalConfigAction $paypalValidator): RedirectResponse
    {
        $validated = $request->validate([
            'default_language' => ['required', 'in:en,ar'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'payments_stripe_enabled' => ['nullable', 'boolean'],
            'payments_paypal_enabled' => ['nullable', 'boolean'],
            'payments_manual_instructions' => ['nullable', 'string'],
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'landing_hero_title' => ['nullable', 'string', 'max:255'],
            'landing_hero_subtitle' => ['nullable', 'string', 'max:255'],
            'landing_hero_title_en' => ['nullable', 'string', 'max:255'],
            'landing_hero_title_ar' => ['nullable', 'string', 'max:255'],
            'landing_hero_subtitle_en' => ['nullable', 'string', 'max:255'],
            'landing_hero_subtitle_ar' => ['nullable', 'string', 'max:255'],
            'landing_feature_1_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_1_description' => ['nullable', 'string'],
            'landing_feature_2_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_2_description' => ['nullable', 'string'],
            'landing_feature_3_title' => ['nullable', 'string', 'max:255'],
            'landing_feature_3_description' => ['nullable', 'string'],
            'landing_instructor_image' => ['nullable', 'image', 'max:2048'],
            'landing_show_hero' => ['nullable', 'boolean'],
            'landing_show_contact_form' => ['nullable', 'boolean'],
            'landing_show_about' => ['nullable', 'boolean'],
            'landing_show_courses_preview' => ['nullable', 'boolean'],
            'landing_show_testimonials' => ['nullable', 'boolean'],
            'landing_show_footer_cta' => ['nullable', 'boolean'],
            'landing_hero_image_mode' => ['nullable', 'in:contain,cover'],
            'landing_hero_image_focus' => ['nullable', 'in:center,top,bottom,left,right'],
            'social_twitter' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
            'legal_terms_en' => ['nullable', 'string'],
            'legal_terms_ar' => ['nullable', 'string'],
            'legal_privacy_en' => ['nullable', 'string'],
            'legal_privacy_ar' => ['nullable', 'string'],
            'auth_google_enabled' => ['nullable', 'boolean'],
            'auth_google_client_id' => ['nullable', 'string'],
            'auth_google_client_secret' => ['nullable', 'string'],
            'security_recaptcha_enabled' => ['nullable', 'boolean'],
            'security_recaptcha_site_key' => ['nullable', 'string'],
            'security_recaptcha_secret_key' => ['nullable', 'string'],
            'contact_whatsapp_enabled' => ['nullable', 'boolean'],
            'contact_whatsapp_phone' => ['nullable', 'string', 'max:32'],
            'contact_whatsapp_message' => ['nullable', 'string', 'max:500'],
        ]);

        $stripeEnabled = $request->boolean('payments_stripe_enabled');
        if ($stripeEnabled && ! app()->environment(['testing', 'dusk', 'dusk.local'])) {
            $publishableKey = (string) config('services.stripe.publishable_key');
            $secretKey = (string) config('services.stripe.secret');
            $webhookSecret = (string) config('services.stripe.webhook_secret');

            $result = $stripeValidator->execute($publishableKey, $secretKey, $webhookSecret);
            if (! ($result['valid'] ?? false)) {
                return back()->withErrors(['stripe' => (string) ($result['message'] ?? 'Stripe configuration is invalid.')]);
            }
        }

        $paypalEnabled = $request->boolean('payments_paypal_enabled');
        if ($paypalEnabled && ! app()->environment(['testing', 'dusk', 'dusk.local'])) {
            $clientId = (string) config('services.paypal.client_id');
            $clientSecret = (string) config('services.paypal.client_secret');
            $baseUrl = (string) config('services.paypal.base_url', '');
            $mode = str_contains(strtolower($baseUrl), 'sandbox') ? 'sandbox' : 'live';

            $result = $paypalValidator->execute($clientId, $clientSecret, $mode);
            if (! ($result['valid'] ?? false)) {
                return back()->withErrors(['paypal' => (string) ($result['message'] ?? 'PayPal configuration is invalid.')]);
            }
        }

        $googleEnabled = $request->boolean('auth_google_enabled');
        if ($googleEnabled) {
            $googleId = (string) ($validated['auth_google_client_id'] ?? '');
            $googleSecret = (string) ($validated['auth_google_client_secret'] ?? '');
            if ($googleId === '' || $googleSecret === '') {
                return back()->withErrors(['auth_google' => __('Google Client ID and Secret are required when Google login is enabled.')])->withInput();
            }
        }

        $recaptchaToggle = $request->boolean('security_recaptcha_enabled');
        if ($recaptchaToggle) {
            $siteKey = (string) ($validated['security_recaptcha_site_key'] ?? '');
            $secretKey = (string) ($validated['security_recaptcha_secret_key'] ?? '');
            if ($siteKey === '' || $secretKey === '') {
                return back()->withErrors(['security_recaptcha' => __('reCAPTCHA Site Key and Secret Key are required when reCAPTCHA is enabled.')])->withInput();
            }
        }

        $values = [
            'site.default_language' => $validated['default_language'],
            'payments.stripe.enabled' => $stripeEnabled,
            'payments.paypal.enabled' => $paypalEnabled,
            'payments.manual.instructions' => $validated['payments_manual_instructions'] ?? '',
            'instructor.name' => $validated['instructor_name'] ?? '',
            'landing.hero_title' => $validated['landing_hero_title'] ?? '',
            'landing.hero_subtitle' => $validated['landing_hero_subtitle'] ?? '',
            'landing.hero_title_en' => $validated['landing_hero_title_en'] ?? '',
            'landing.hero_title_ar' => $validated['landing_hero_title_ar'] ?? '',
            'landing.hero_subtitle_en' => $validated['landing_hero_subtitle_en'] ?? '',
            'landing.hero_subtitle_ar' => $validated['landing_hero_subtitle_ar'] ?? '',
            'landing.feature_1_title' => $validated['landing_feature_1_title'] ?? '',
            'landing.feature_1_description' => $validated['landing_feature_1_description'] ?? '',
            'landing.feature_2_title' => $validated['landing_feature_2_title'] ?? '',
            'landing.feature_2_description' => $validated['landing_feature_2_description'] ?? '',
            'landing.feature_3_title' => $validated['landing_feature_3_title'] ?? '',
            'landing.feature_3_description' => $validated['landing_feature_3_description'] ?? '',
            'landing.show_hero' => $request->boolean('landing_show_hero'),
            'landing.show_contact_form' => $request->boolean('landing_show_contact_form'),
            'landing.show_about' => $request->boolean('landing_show_about'),
            'landing.show_courses_preview' => $request->boolean('landing_show_courses_preview'),
            'landing.show_testimonials' => $request->boolean('landing_show_testimonials'),
            'landing.show_footer_cta' => $request->boolean('landing_show_footer_cta'),
            'landing.hero_image_mode' => $validated['landing_hero_image_mode'] ?? 'contain',
            'landing.hero_image_focus' => $validated['landing_hero_image_focus'] ?? 'center',
            'instructor.social.twitter' => $validated['social_twitter'] ?? '',
            'instructor.social.instagram' => $validated['social_instagram'] ?? '',
            'instructor.social.youtube' => $validated['social_youtube'] ?? '',
            'instructor.social.linkedin' => $validated['social_linkedin'] ?? '',
            'auth.google.enabled' => $googleEnabled,
            'auth.google.client_id' => (string) ($validated['auth_google_client_id'] ?? ''),
            'auth.google.client_secret' => (string) ($validated['auth_google_client_secret'] ?? ''),
            'security.recaptcha.enabled' => $recaptchaToggle,
            'security.recaptcha.site_key' => (string) ($validated['security_recaptcha_site_key'] ?? ''),
            'security.recaptcha.secret_key' => (string) ($validated['security_recaptcha_secret_key'] ?? ''),
            'contact.whatsapp.enabled' => $request->boolean('contact_whatsapp_enabled'),
            'contact.whatsapp.phone' => (string) ($validated['contact_whatsapp_phone'] ?? ''),
            'contact.whatsapp.message' => (string) ($validated['contact_whatsapp_message'] ?? ''),
        ];
        if (array_key_exists('legal_terms_en', $validated)) {
            $values['legal.terms.en'] = $validated['legal_terms_en'];
        }
        if (array_key_exists('legal_terms_ar', $validated)) {
            $values['legal.terms.ar'] = $validated['legal_terms_ar'];
        }
        if (array_key_exists('legal_privacy_en', $validated)) {
            $values['legal.privacy.en'] = $validated['legal_privacy_en'];
        }
        if (array_key_exists('legal_privacy_ar', $validated)) {
            $values['legal.privacy.ar'] = $validated['legal_privacy_ar'];
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $values['site.logo_path'] = $path;
        }

        if ($request->hasFile('landing_instructor_image')) {
            $path = $request->file('landing_instructor_image')->store('landing', 'public');
            $values['landing.instructor_image'] = $path;
        }

        $settings->set($values);

        if ($paypalEnabled) {
            return back()->with('status', 'PayPal is connected successfully.');
        }

        if ($stripeEnabled) {
            return back()->with('status', 'Stripe is connected successfully.');
        }

        return back()->with('status', 'Settings updated.');
    }
}
