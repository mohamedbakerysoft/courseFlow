📘 FINAL PRD — PRODUCT REQUIREMENTS DOCUMENT

Project Name (Working Title)

CourseFlow

Sell Your Video Course. Simply. Securely.

⸻

IMPORTANT

This document defines a complete Laravel-based video course delivery system.

The goal is to build a simple, fast, single-instructor course platform using:
	•	Laravel (backend)
	•	MySQL (database)
	•	Blade templates (frontend)
	•	Alpine.js (light interactivity)
	•	Tailwind CSS (styling)

The system must be clean, lightweight, commercially secure, and ready for CodeCanyon distribution.

⸻

1. PRODUCT OVERVIEW

Build a single-instructor video course platform that allows a content creator or teacher to sell video courses online without WordPress and without SaaS platforms.

The system focuses on:
	•	Video-based courses (YouTube / Vimeo embed)
	•	Paid and free courses
	•	Simple dashboards
	•	Secure access
	•	Student progress tracking
	•	Manual & automatic payments
	•	Arabic & English support (RTL ready)

This system is NOT:
	•	A marketplace
	•	A multi-instructor LMS
	•	A SaaS with subscriptions

⸻

2. TARGET USERS

Admin / Instructor (same person)
	•	Owns the platform
	•	Creates and manages courses
	•	Manages payments
	•	Moderates comments
	•	Controls ads and settings

Student
	•	Registers and logs in
	•	Purchases or enrolls in courses
	•	Watches lessons
	•	Tracks progress
	•	Comments and asks questions

⸻

3. TECH STACK REQUIREMENTS
	•	Backend: Laravel (latest stable)
	•	Database: MySQL
	•	Frontend:
	•	Blade templates
	•	Alpine.js (no Vue / React)
	•	Tailwind CSS
	•	Authentication: Laravel Auth
	•	Payments: Stripe + PayPal + Manual
	•	Localization: Laravel Localization
	•	RTL support: Required

⸻

4. CORE PRODUCT PRINCIPLES
	•	Single instructor only
	•	Simple over complex
	•	No WordPress dependency
	•	No SaaS lock-in
	•	Commercial-grade protection (no fake “100% secure” claims)
	•	Compatible with shared hosting

⸻

5. PHASED DELIVERY (CRITICAL)

The system MUST be built in phases.
Do NOT implement all features at once.

⸻

🔹 PHASE 1 — MVP (REQUIRED FOR FIRST RELEASE)

Goal

Deliver a fully usable, sellable course system with the smallest stable feature set.

⸻

5.1 Authentication & Roles
	•	Register
	•	Login
	•	Password reset
	•	Optional email verification
	•	Roles:
	•	Admin
	•	Student

⸻

5.2 Instructor Profile & Static Pages
	•	Public instructor profile:
	•	Profile image
	•	Full name
	•	Bio / description
	•	Optional social links
	•	List of published courses
	•	Static pages:
	•	About the Instructor
	•	Terms & Conditions
	•	Privacy Policy
	•	Editable from admin dashboard

⸻

5.3 Course Management

Each course includes:
	•	Title
	•	Description (rich text)
	•	Thumbnail image
	•	Price
	•	Currency
	•	Free or Paid flag
	•	Language
	•	Status (Draft / Published)

Important rule:
Even free courses must require enrollment (price = 0) to create an enrollment record.

⸻

5.4 Lessons & Video Delivery
	•	Lessons belong to a course
	•	Each lesson includes:
	•	Title
	•	Description
	•	YouTube or Vimeo embed URL

Video protection (commercial-grade):
	•	Internal embedded player
	•	Disable “open on YouTube”
	•	Disable share button
	•	Dynamic watermark (student name or email)
	•	Access only for logged-in enrolled users

⸻

5.5 Course Access Control
	•	Middleware-based access protection
	•	Students can only access purchased/enrolled courses
	•	Unauthorized access must redirect correctly

⸻

5.6 Progress Tracking
	•	Track lesson completion per student
	•	Store progress in database
	•	Display:
	•	Course progress percentage
	•	Resume last watched lesson
	•	Student dashboard shows progress bars

⸻

5.7 Payments (Core)

Automatic payments:
	•	Stripe
	•	PayPal

Manual payments:
	•	Bank transfer
	•	InstaPay
	•	Vodafone Cash
	•	Custom instructions

Manual flow:
	•	Student submits payment request
	•	Admin manually approves enrollment
	•	Admin can add payment notes

⸻

5.8 Admin Dashboard (MVP Scope)
	•	Manage courses
	•	Manage lessons
	•	Manage students
	•	Manage payments
	•	Manage static pages

⸻

🔹 PHASE 2 — ENGAGEMENT & MONETIZATION

Goal

Improve interaction, retention, and monetization without increasing system complexity.

⸻

6.1 Comments & Q&A
	•	Comments under each lesson
	•	Nested replies
	•	Students can reply to each other
	•	No friends system
	•	No private messages

Moderation:
	•	Approve / delete / block comments
	•	Disable comments per course

⸻

6.2 Ads Management
	•	Admin-defined ad slots:
	•	Before video
	•	After video
	•	Sidebar
	•	Supports:
	•	Google AdSense
	•	Custom HTML / JS
	•	Ads must not break video playback

⸻

6.3 Theme Control
	•	Light mode
	•	Dark mode
	•	User toggle
	•	Store preference per user

⸻

6.4 Multi-Language Support
	•	English + Arabic included
	•	RTL fully supported
	•	Default language configurable
	•	Easy to add new languages

⸻

🔹 PHASE 3 — OPTIONAL FUTURE UPDATES (OUT OF SCOPE)

These features must NOT be implemented unless explicitly required later:
	•	Certificates
	•	Coupons
	•	Course bundles
	•	Subscriptions
	•	Live classes
	•	Mobile apps
	•	Advanced analytics
	•	Multi-instructor support

⸻

7. NON-FUNCTIONAL REQUIREMENTS
	•	Clean folder structure
	•	Clear naming conventions
	•	No unnecessary dependencies
	•	Easy installation
	•	Shared-hosting compatible
	•	Docker optional (development only)
	•	No required background workers
	•	No Redis dependency

⸻

8. DEMO & SEEDING REQUIREMENTS

Seeders must create:
	•	Admin account
	•	Instructor profile
	•	Sample course
	•	Sample lessons
	•	Sample student
	•	Sample enrollment
	•	Sample progress
	•	Sample comments (Phase 2)

The demo must be usable immediately after installation.

⸻

9. OUT OF SCOPE (ABSOLUTE)

The system must NOT include:
	•	Marketplace features
	•	Multi-instructor logic
	•	SaaS billing
	•	Subscriptions
	•	Over-engineered abstractions

⸻

10. FINAL GOAL

Deliver a clean, fast, sellable Laravel script that:
	•	Solves a real problem
	•	Is easy to run locally
	•	Is easy to understand
	•	Is easy to maintain
	•	Is attractive to CodeCanyon buyers

⸻

FINAL NOTE

Do not move to Phase 2 or Phase 3 unless Phase 1 is fully complete, tested, and stable.
