<?php

namespace App\Actions\Pages;

use App\Models\Page;
use App\Services\SettingsService;

class ShowPageAction
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    public function execute(string $slug): Page
    {
        if (in_array($slug, ['terms', 'privacy'], true)) {
            $locale = app()->getLocale();
            $localeKey = $locale === 'ar' ? 'ar' : 'en';
            $key = "legal.$slug.$localeKey";
            $content = (string) $this->settings->get($key, '');
            if (trim($content) === '') {
                $content = $this->defaultLegalContent($slug, $localeKey);
            }
            $title = $slug === 'terms'
                ? ($localeKey === 'ar' ? 'شروط الخدمة' : 'Terms of Service')
                : ($localeKey === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy');
            $page = new Page;
            $page->fill([
                'slug' => $slug,
                'title' => $title,
                'content' => $content,
            ]);

            return $page;
        }

        return Page::query()->where('slug', $slug)->firstOrFail();
    }

    protected function defaultLegalContent(string $slug, string $locale): string
    {
        if ($slug === 'terms') {
            return $locale === 'ar'
                ? "1. المقدمة\nباستخدام هذا الموقع، فإنك توافق على الشروط التالية لاستخدام الخدمة.\n\n2. حسابات المستخدمين\nأنت مسؤول عن معلومات تسجيل الدخول الخاصة بك وعن الاستخدام الصحيح للحساب.\n\n3. المدفوعات والاستردادات\nيتم منح الوصول إلى الدورات بعد إتمام الدفع بنجاح. قد تتوفر سياسة استرداد وفقاً لما هو مذكور في صفحة الدورة.\n\n4. الملكية الفكرية\nجميع المواد التعليمية مرخصة للاستخدام الشخصي فقط، ويحظر إعادة نشرها أو مشاركتها بدون إذن.\n\n5. تحديد المسؤولية\nيُقدّم المحتوى التعليمي كما هو. لا نتحمل مسؤولية أي خسائر غير مباشرة أو تبعية ناتجة عن الاستخدام.\n\n6. معلومات الاتصال\nللاستفسارات، يرجى استخدام نموذج الاتصال داخل الموقع."
                : "1. Introduction\nBy using this site, you agree to the following terms of service.\n\n2. User accounts\nYou are responsible for your login credentials and for proper use of your account.\n\n3. Payments & refunds\nAccess to courses is granted after successful payment. A refund policy may be available as stated on the course page.\n\n4. Intellectual property\nAll learning materials are licensed for personal use only. Redistribution or sharing without permission is prohibited.\n\n5. Limitation of liability\nContent is provided as-is. We are not liable for indirect or consequential losses arising from use.\n\n6. Contact info\nFor questions, please use the contact form on the site.";
        }

        return $locale === 'ar'
            ? "1. البيانات التي نجمعها\nنجمع معلومات الحساب الأساسية، بيانات الدفع عند الحاجة، وبيانات الاستخدام لتحسين الخدمة.\n\n2. كيفية استخدام البيانات\nنستخدم البيانات لتقديم الخدمة، تحسين التجربة، وتعزيز الأمان، بما يتوافق مع القوانين المعمول بها (مثل GDPR حيث ينطبق).\n\n3. ملفات تعريف الارتباط\nنستخدم ملفات تعريف الارتباط لتذكر التفضيلات وتحليل الاستخدام. يمكنك تعطيلها من إعدادات المتصفح.\n\n4. الخدمات الخارجية (Stripe وPayPal وGoogle)\nنستخدم مزودي دفع وخدمات تحليلات وربما خدمات استضافة فيديو. تخضع بياناتك لسياسات هذه الجهات.\n\n5. حقوق المستخدم\nيمكنك طلب الوصول إلى بياناتك أو تحديثها أو حذفها وفقاً للقانون المعمول به."
            : "1. Data we collect\nWe collect basic account details, payment data when required, and usage data to improve the service.\n\n2. How we use data\nWe use data to provide the service, improve the experience, and enhance security, in compliance with applicable laws (e.g., GDPR where applicable).\n\n3. Cookies\nWe use cookies to remember preferences and analyze usage. You can disable cookies in your browser settings.\n\n4. Third‑party services (Stripe, PayPal, Google)\nWe use payment providers, analytics, and possibly video hosting. Your data is subject to their policies.\n\n5. User rights\nYou may request access, correction, or deletion of your data, subject to applicable law.";
    }
}
