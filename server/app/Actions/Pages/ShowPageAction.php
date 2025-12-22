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
            $page = new Page();
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
                ? "1. المقدمة\nباستخدام هذا الموقع، فإنك توافق على هذه الشروط.\n\n2. حسابات المستخدمين\nأنت مسؤول عن الحفاظ على سرية بيانات الدخول وعدم إساءة الاستخدام.\n\n3. الوصول إلى الدورات والمدفوعات\nيتم منح الوصول إلى الدورات عند إتمام الدفع أو التسجيل المجاني وفقاً للوصف.\n\n4. سياسة الاسترداد\nقد يتم تقديم استرداد وفق سياسة المعلم المنصوص عليها في صفحة الدورة، مع مراعاة الاستخدام المعقول.\n\n5. الملكية الفكرية\nجميع المواد التعليمية مرخصة للاستخدام الشخصي فقط ولا يجوز إعادة توزيعها أو مشاركتها.\n\n6. الإنهاء\nيجوز لنا تعليق أو إنهاء الوصول عند إساءة الاستخدام أو مخالفة الشروط.\n\n7. معلومات الاتصال\nيمكنك التواصل عبر نموذج الاتصال داخل الموقع."
                : "1. Introduction\nBy using this site, you agree to these terms.\n\n2. User Accounts\nYou are responsible for your login credentials and agree not to misuse the platform.\n\n3. Course Access & Payments\nAccess to courses is granted upon valid payment or free enrollment as described.\n\n4. Refund Policy\nRefunds may be offered according to the instructor’s policy stated on the course page, subject to reasonable use.\n\n5. Intellectual Property\nAll learning materials are licensed for personal use only and may not be redistributed or shared.\n\n6. Termination\nWe may suspend or terminate access in cases of misuse or violation of these terms.\n\n7. Contact Information\nYou can reach us using the contact form on the site.";
        }

        return $locale === 'ar'
            ? "1. المعلومات التي نجمعها\nنقوم بجمع معلومات الحساب الأساسية، بيانات الدفع عند الحاجة، وبيانات الاستخدام لتحسين الخدمة.\n\n2. كيفية استخدام المعلومات\nنستخدم البيانات لتقديم الخدمة، تحسين التجربة، وضمان الأمان وإبلاغك بالتحديثات.\n\n3. ملفات تعريف الارتباط\nنستخدم ملفات تعريف الارتباط لتذكر تفضيلاتك وتحليل الاستخدام. يمكنك تعطيلها من إعدادات المتصفح.\n\n4. الخدمات الخارجية\nقد نستخدم موفري الدفع والتحليلات وخدمات استضافة الفيديو. تخضع بياناتك لسياسات هذه الخدمات.\n\n5. أمان البيانات\nنتخذ تدابير معقولة لحماية البيانات، دون ضمان حماية مطلقة.\n\n6. حقوق المستخدم\nيمكنك طلب تحديث أو حذف بياناتك وفقاً للقانون المعمول به.\n\n7. الاتصال\nيرجى استخدام نموذج الاتصال داخل الموقع للتواصل."
            : "1. Information We Collect\nWe collect basic account details, payment data when required, and usage data to improve the service.\n\n2. How We Use Information\nWe use data to provide the service, enhance the experience, ensure security, and communicate updates.\n\n3. Cookies\nWe use cookies to remember preferences and analyze usage. You can disable cookies in your browser settings.\n\n4. Third-Party Services\nWe may use payment providers, analytics, and video hosting. Your data is subject to their policies.\n\n5. Data Security\nWe take reasonable measures to protect data without guaranteeing absolute security.\n\n6. User Rights\nYou may request to update or delete your data, subject to applicable law.\n\n7. Contact\nPlease use the site’s contact form to reach us.";
    }
}
