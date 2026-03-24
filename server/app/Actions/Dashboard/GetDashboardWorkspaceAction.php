<?php

namespace App\Actions\Dashboard;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Collection;

class GetDashboardWorkspaceAction
{
    public function execute(User $user): array
    {
        if ($user->role !== User::ROLE_ADMIN) {
            return $this->studentWorkspace($user);
        }

        return $this->adminWorkspace($user);
    }

    private function studentWorkspace(User $user): array
    {
        return [
            'isInstructor' => false,
            'enrolledCourses' => $user->enrolledCoursesForDashboard(),
        ];
    }

    private function adminWorkspace(User $user): array
    {
        $courseIds = Course::instructorCourseIds($user);
        $totalCourses = Course::countForInstructor($user, Course::TYPE_COURSE);
        $totalBooks = Course::countForInstructor($user, Course::TYPE_BOOK);
        $draftCoursesCount = Course::countForInstructor($user, Course::TYPE_COURSE, Course::STATUS_DRAFT);
        $draftBooksCount = Course::countForInstructor($user, Course::TYPE_BOOK, Course::STATUS_DRAFT);
        $publishedCoursesCount = Course::countForInstructor($user, null, Course::STATUS_PUBLISHED);
        $totalLessons = Lesson::countForCourses($courseIds);
        $totalStudents = User::distinctEnrolledCountForCourses($courseIds);
        $pendingManualPaymentsCount = Payment::pendingManualRequestsCount();
        $latestDraftCourse = Course::latestDraftForInstructor($user);
        $latestDraftLesson = Lesson::latestDraftForCourses($courseIds);
        $recentCourses = Course::recentForInstructor($user);
        $recentLessons = Lesson::recentForCourses($courseIds);
        $recentStudents = User::recentStudents();
        $recentManualRequests = Payment::recentManualRequests();
        $recentPaidPayments = Payment::recentPaidPayments();
        $draftItemsCount = $draftCoursesCount + $draftBooksCount + ($latestDraftLesson ? 1 : 0);

        return [
            'isInstructor' => true,
            'stats' => $this->buildStats(
                $totalCourses,
                $totalBooks,
                $totalLessons,
                $totalStudents,
                $pendingManualPaymentsCount,
                $draftItemsCount
            ),
            'attentionItems' => $this->buildAttentionItems(
                $pendingManualPaymentsCount,
                $latestDraftCourse,
                $latestDraftLesson,
                $publishedCoursesCount,
                $totalCourses
            ),
            'quickLinks' => $this->buildQuickLinks(
                $totalCourses,
                $totalBooks,
                $pendingManualPaymentsCount,
                $draftItemsCount
            ),
            'draftItems' => $this->buildDraftItems($latestDraftCourse, $latestDraftLesson, $draftBooksCount),
            'recentProducts' => $this->mapRecentProducts($recentCourses),
            'recentLessons' => $this->mapRecentLessons($recentLessons),
            'recentStudents' => $this->mapRecentStudents($recentStudents),
            'recentManualRequests' => $this->mapRecentManualRequests($recentManualRequests),
            'recentPaidPayments' => $this->mapRecentPaidPayments($recentPaidPayments),
        ];
    }

    private function buildStats(
        int $totalCourses,
        int $totalBooks,
        int $totalLessons,
        int $totalStudents,
        int $pendingManualPaymentsCount,
        int $draftItemsCount
    ): array {
        return [
            [
                'label' => 'Courses',
                'value' => $totalCourses,
                'hint' => 'Published and draft teaching products',
            ],
            [
                'label' => 'Books',
                'value' => $totalBooks,
                'hint' => 'Downloadable products in your catalog',
            ],
            [
                'label' => 'Lessons',
                'value' => $totalLessons,
                'hint' => 'All lessons across your courses',
            ],
            [
                'label' => 'Students',
                'value' => $totalStudents,
                'hint' => 'Unique learners enrolled in your content',
            ],
            [
                'label' => 'Manual payments',
                'value' => $pendingManualPaymentsCount,
                'hint' => 'Requests waiting for review',
            ],
            [
                'label' => 'Draft items',
                'value' => $draftItemsCount,
                'hint' => 'Products or lessons still not live',
            ],
        ];
    }

    private function buildAttentionItems(
        int $pendingManualPaymentsCount,
        ?Course $latestDraftCourse,
        ?Lesson $latestDraftLesson,
        int $publishedCoursesCount,
        int $totalCourses
    ): array {
        $items = [];

        if ($pendingManualPaymentsCount > 0) {
            $items[] = [
                'title' => 'Review manual payment requests',
                'description' => $pendingManualPaymentsCount.' request(s) are waiting for approval before students can access paid courses.',
                'url' => route('dashboard.finance.manual_payments'),
                'action' => 'Open requests',
                'badge' => 'Priority',
            ];
        }

        if ($latestDraftCourse) {
            $items[] = [
                'title' => 'Finish your latest draft product',
                'description' => $latestDraftCourse->title.' is still saved as draft and not visible to customers yet.',
                'url' => $this->productEditUrl($latestDraftCourse),
                'action' => 'Edit draft',
                'badge' => strtoupper((string) $latestDraftCourse->product_type),
            ];
        }

        if ($latestDraftLesson) {
            $items[] = [
                'title' => 'Complete the latest draft lesson',
                'description' => $latestDraftLesson->title.' still needs to be reviewed and published inside '.$latestDraftLesson->course?->title.'.',
                'url' => route('dashboard.lessons.edit', $latestDraftLesson),
                'action' => 'Open lesson',
                'badge' => 'Lesson',
            ];
        }

        if ($totalCourses > 0 && $publishedCoursesCount === 0) {
            $items[] = [
                'title' => 'Publish your first product',
                'description' => 'You already have products in progress, but nothing is live yet on the storefront.',
                'url' => route('dashboard.courses.index'),
                'action' => 'Review products',
                'badge' => 'Launch',
            ];
        }

        return $items;
    }

    private function buildQuickLinks(
        int $totalCourses,
        int $totalBooks,
        int $pendingManualPaymentsCount,
        int $draftItemsCount
    ): array {
        return [
            [
                'title' => 'Courses',
                'description' => 'Create, edit, publish, and manage all course products.',
                'url' => route('dashboard.courses.index'),
                'meta' => $totalCourses.' total',
            ],
            [
                'title' => 'Books',
                'description' => 'Manage downloadable books and workbook offers.',
                'url' => route('dashboard.books.index'),
                'meta' => $totalBooks.' total',
            ],
            [
                'title' => 'Lessons',
                'description' => 'Open course lesson managers to organize modules, reorder lessons, and publish content.',
                'url' => route('dashboard.courses.index'),
                'meta' => 'Managed from each course',
            ],
            [
                'title' => 'Users',
                'description' => 'Review students, grant access, and manage account status.',
                'url' => route('dashboard.users.index'),
                'meta' => 'Students and admins',
            ],
            [
                'title' => 'Finance insights',
                'description' => 'Check sales totals, paid enrollments, and product performance.',
                'url' => route('dashboard.finance.index'),
                'meta' => 'Revenue and sales',
            ],
            [
                'title' => 'Manual payments',
                'description' => 'Approve or reject transfer proof submissions quickly.',
                'url' => route('dashboard.finance.manual_payments'),
                'meta' => $pendingManualPaymentsCount.' pending',
            ],
            [
                'title' => 'Landing page controls',
                'description' => 'Update hero content, sections, payment instructions, and storefront copy.',
                'url' => route('dashboard.settings.edit'),
                'meta' => 'Settings > Landing',
            ],
            [
                'title' => 'FAQs',
                'description' => 'Edit common questions, answers, visibility, and ordering.',
                'url' => route('dashboard.faqs.index'),
                'meta' => 'Public FAQ page',
            ],
            [
                'title' => 'Appearance',
                'description' => 'Adjust branding colors, visuals, and storefront presentation.',
                'url' => route('dashboard.appearance.edit'),
                'meta' => 'Theme and media',
            ],
            [
                'title' => 'Settings',
                'description' => 'Control payments, authentication, contact options, and legal pages.',
                'url' => route('dashboard.settings.edit'),
                'meta' => 'Platform configuration',
            ],
            [
                'title' => 'Menus',
                'description' => 'Rename and reorder header links with drag and drop.',
                'url' => route('dashboard.menus.edit'),
                'meta' => 'Header navigation',
            ],
        ];
    }

    private function buildDraftItems(?Course $latestDraftCourse, ?Lesson $latestDraftLesson, int $draftBooksCount): array
    {
        $items = [];

        if ($latestDraftCourse) {
            $items[] = [
                'title' => $latestDraftCourse->title,
                'description' => 'Latest draft product waiting for edits or publishing.',
                'url' => $this->productEditUrl($latestDraftCourse),
                'action' => 'Edit product',
                'badge' => strtoupper((string) $latestDraftCourse->product_type),
            ];
        }

        if ($latestDraftLesson) {
            $items[] = [
                'title' => $latestDraftLesson->title,
                'description' => 'Draft lesson inside '.$latestDraftLesson->course?->title.'.',
                'url' => route('dashboard.lessons.edit', $latestDraftLesson),
                'action' => 'Edit lesson',
                'badge' => 'LESSON',
            ];
        }

        if ($draftBooksCount > 0) {
            $items[] = [
                'title' => $draftBooksCount.' draft book(s)',
                'description' => 'You have downloadable products still hidden from the storefront.',
                'url' => route('dashboard.books.index'),
                'action' => 'Open books',
                'badge' => 'BOOKS',
            ];
        }

        return $items;
    }

    private function mapRecentProducts(Collection $products): array
    {
        return $products->map(fn (Course $course) => [
            'title' => $course->title,
            'description' => ucfirst((string) $course->product_type).' · '.ucfirst((string) $course->status),
            'meta' => optional($course->updated_at)->diffForHumans(),
            'url' => $this->productEditUrl($course),
            'action' => 'Open',
        ])->all();
    }

    private function mapRecentLessons(Collection $lessons): array
    {
        return $lessons->map(fn (Lesson $lesson) => [
            'title' => $lesson->title,
            'description' => ($lesson->course?->title ?? 'Course').' · '.ucfirst((string) $lesson->status),
            'meta' => optional($lesson->updated_at)->diffForHumans(),
            'url' => route('dashboard.lessons.edit', $lesson),
            'action' => 'Edit',
        ])->all();
    }

    private function mapRecentStudents(Collection $students): array
    {
        return $students->map(fn (User $student) => [
            'title' => $student->name,
            'description' => $student->email,
            'meta' => 'Joined '.optional($student->created_at)->diffForHumans(),
            'url' => route('dashboard.users.show', $student),
            'action' => 'View',
        ])->all();
    }

    private function mapRecentManualRequests(Collection $payments): array
    {
        return $payments->map(fn (Payment $payment) => [
            'title' => ($payment->user?->name ?? 'Student').' · '.($payment->course?->title ?? 'Course'),
            'description' => 'Manual payment · '.ucfirst((string) $payment->status),
            'meta' => optional($payment->created_at)->diffForHumans(),
            'url' => route('dashboard.finance.manual_payments'),
            'action' => 'Review',
        ])->all();
    }

    private function mapRecentPaidPayments(Collection $payments): array
    {
        return $payments->map(fn (Payment $payment) => [
            'title' => ($payment->course?->title ?? 'Course').' · '.number_format((float) $payment->amount, 2).' '.$payment->currency,
            'description' => ($payment->user?->name ?? 'Student').' paid via '.ucfirst((string) $payment->provider).'.',
            'meta' => optional($payment->created_at)->diffForHumans(),
            'url' => route('dashboard.finance.index'),
            'action' => 'Open',
        ])->all();
    }

    private function productEditUrl(Course $course): string
    {
        if ($course->product_type === Course::TYPE_BOOK) {
            return route('dashboard.books.edit', $course);
        }

        return route('dashboard.courses.edit', $course);
    }
}
