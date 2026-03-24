# AGENTS.md

## Mission
Work as a senior Laravel engineer in a production codebase.
The goal is to keep the application clean, scalable, maintainable, and safe for long-term growth.
Every change must improve code quality or at minimum preserve it.
When touching old code, refactor it within the touched scope to align with these rules.

---

## Core Principles
- Prefer clarity over cleverness.
- Prefer maintainability over shortcuts.
- Prefer small focused classes and methods over large files.
- Prefer explicit naming over vague naming.
- Prefer composition over duplication.
- Prefer incremental refactoring over uncontrolled rewrites.
- Keep diffs as small as possible, but as clean as necessary.
- Never add technical debt when a cleaner structure is reasonably possible.

---

## Architecture Rules

### 1) Controllers must stay thin
Controllers are orchestration only.

Controllers may:
- receive the request
- call Form Request validation
- instantiate or call a Service / Action / Use Case
- return a response / redirect / resource / view

Controllers must NOT:
- contain business logic
- contain raw database queries
- build large arrays of domain data
- perform complex conditionals
- contain long methods
- manipulate unrelated models directly

Preferred controller flow:
1. Request validation via Form Request
2. Convert validated input to DTO / Data object if useful
3. Call Service / Action
4. Return formatted response

---

### 2) Business logic belongs in Services / Actions
All business rules must live outside controllers.

Use:
- `app/Services` for reusable business workflows
- `app/Actions` for single-purpose operations
- `app/Support` for shared helpers with real value

Use Services when:
- multiple steps are needed
- multiple models are involved
- the logic may be reused
- the flow represents a business process

Use Actions when:
- one focused task is being performed
- the logic is small but still deserves separation

Examples:
- EnrollUserInCourse
- ApproveManualPayment
- ReorderCourseLessons
- UpdateLandingPageSections

---

### 3) Queries must live in the right place
Do NOT scatter queries across controllers, Blade views, or random classes.

#### Preferred query placement:
- Simple model-specific queries: Eloquent scopes on the Model
- Reusable read/query logic: Query objects or Repository-like classes in `app/Queries`
- Complex cross-model retrieval for a feature: dedicated Query class
- Service classes may coordinate queries, but they should not become query dumps

#### Rules:
- Never write raw DB queries in Blade
- Never write database queries in controllers
- Avoid large query chains inline inside services if they can be named and reused
- If a query expresses business meaning, give it a name

Examples:
- `Course::published()->featured()->latest()`
- `Lesson::forCourse($courseId)->ordered()`
- `app/Queries/Admin/Finance/ManualPaymentRequestsQuery.php`

#### When to use Model scopes
Use scopes for:
- readable model filtering
- common conditions
- composable query fragments

Examples:
- `published()`
- `visible()`
- `ordered()`
- `enrolledBy($user)`

#### When to use Query classes
Use a dedicated query class if:
- the query is long
- joins become complex
- filtering/sorting is dynamic
- the query is feature-specific
- the query will likely grow over time

---

### 4) Use DTO / Data / Entity objects for structured input/output
Use Data Transfer Objects for validated structured data moving into services.

Purpose:
- reduce array chaos
- improve readability
- make service method signatures cleaner
- keep business workflows explicit

Place them in:
- `app/Data`
- `app/DTOs`

Examples:
- `CreateCourseData`
- `UpdateCourseData`
- `ManualPaymentSubmissionData`
- `LandingSectionData`

Rules:
- DTOs must be simple and predictable
- DTOs must not contain database queries
- DTOs represent validated data, not behavior-heavy domain models

---

### 5) Form Requests are mandatory for validation
All non-trivial validation must use Form Request classes.

Rules:
- Controllers should not contain inline validation unless it is truly tiny
- Validation rules belong in Form Requests
- Authorization logic belongs in Form Requests or Policies
- Use custom rule objects when validation becomes complex

Place them in:
- `app/Http/Requests`

Examples:
- `StoreCourseRequest`
- `UpdateLessonRequest`
- `ApproveManualPaymentRequest`

---

### 6) Keep Blade views dumb
Blade views are for presentation only.

Blade must NOT:
- run queries
- contain business logic
- contain large conditional trees that should be prepared beforehand
- transform domain data heavily

Blade may:
- render already prepared data
- use small presentation conditionals
- call presentational components

If the view needs too much logic:
- move formatting to ViewModels / Presenters / dedicated prepared data
- move domain logic to services/actions
- move repeated UI into Blade components

---

## File and Class Size Rules

### 7) No fat files
Files must remain easy to scan.

Guidelines:
- Controllers: ideally small and focused
- Services: one business workflow, not a god object
- Models: keep them expressive but not overloaded
- Blade files: split large sections into partials/components
- JS modules: split by feature
- CSS: prefer structured component-based organization

### 8) No fat methods
Methods must be short and readable.

Target:
- Prefer methods under 20 lines
- If a method grows beyond ~20 lines, split it unless there is a strong readability reason
- If it grows beyond ~30 lines, refactor is required

Split when you see:
- multiple responsibilities
- repeated branches
- long validation or mapping
- query + business logic mixed together
- transformation + persistence mixed together

Good methods should do one thing and have a name that explains that thing.

---

## Model Rules

### 9) Models should stay expressive, not overloaded
Models may contain:
- relationships
- scopes
- casts
- accessors/mutators
- small domain helpers tightly related to the model

Models should NOT become:
- service containers
- workflow engines
- giant business-logic buckets

Allowed in Model:
- `isPublished()`
- `scopePublished()`
- `scopeOrdered()`
- `isEnrolledBy(User $user)`

Not ideal in Model:
- multi-step checkout workflows
- manual payment approval flows
- large cross-aggregate orchestration

---

## Domain Boundaries

### 10) Separate write logic from read logic when needed
For larger features:
- write/update flows belong in Services/Actions
- complex reading/filtering belongs in Query classes

This keeps logic clean and avoids giant mixed classes.

---

## Naming Rules

### 11) Naming must be explicit
Use names that describe intent.

Good:
- `ApproveManualPayment`
- `ReorderLessonsAction`
- `CourseEnrollmentService`
- `PublishedCoursesQuery`

Bad:
- `Helper`
- `Manager`
- `ProcessData`
- `HandleStuff`
- `DoAction`

Variables must also be explicit:
- prefer `$manualPaymentRequest`
- avoid `$data` if a better name exists
- avoid `$item`, `$row`, `$temp` unless truly generic

---

## Database and Eloquent Rules

### 12) Eloquent usage must be disciplined
- Always eager load relationships when needed
- Avoid N+1 queries
- Avoid repeated queries in loops
- Prefer scopes for common query logic
- Prefer transactions for multi-step write operations
- Use route model binding where it improves clarity
- Use database constraints where appropriate

### 13) Raw SQL / DB facade
Use raw SQL only when:
- there is a clear performance reason
- Eloquent would be significantly worse
- the query is still readable and maintainable

If raw SQL is used:
- isolate it
- document why
- do not scatter it around the app

---

## Refactoring Rules

### 14) Refactor touched legacy code
When changing old code:
- do not rewrite the entire application
- but do clean the touched area to align with these rules

Examples:
- move inline validation into Form Request
- extract repeated logic into a service/action
- split large methods
- replace duplicated queries with scopes/query classes
- remove dead code in the touched scope
- rename unclear methods/variables if affected by the task

Rule:
Leave the touched code better than you found it.

### 15) Automatic cleanup expectation
For every request:
- inspect the touched files
- if you find outdated patterns in the touched scope, refactor them safely
- do not leave obvious duplication or fat methods behind
- do not break behavior while cleaning structure

---

## Response and View Preparation

### 16) Prepare data before the view
If the UI needs formatted or combined data:
- prepare it in a service, presenter, or dedicated data builder
- do not assemble large complex structures inside Blade

Optional patterns allowed:
- ViewModels
- Presenters
- Resource classes
- dedicated section builders for landing/admin pages

---

## Frontend and Blade Structure

### 17) Frontend must be modular
- Extract repeated sections into Blade components or partials
- Keep section files focused
- Avoid giant `welcome.blade.php` or giant admin views when possible
- Break long pages into components/partials by section

### 18) Theme-safe UI
- No hardcoded colors if theme tokens/design tokens already exist
- Ensure dark/light mode compatibility
- New UI must respect existing design system
- Avoid inline styles unless absolutely necessary

---

## Admin and CRUD Rules

### 19) Admin features must be manageable and scalable
When adding admin-editable content:
- store content in structured fields or related tables
- avoid hardcoding content in Blade
- allow future extensibility
- use repeatable structured data where appropriate
- use drag-and-drop ordering where it improves UX over manual sort fields

Examples:
- landing page sections
- testimonials
- FAQ items
- course curriculum ordering
- manual payment requests

---

## Error Handling and Safety

### 20) Handle edge cases explicitly
Always consider:
- null values
- missing relations
- deleted records
- unauthorized access
- invalid states
- duplicate actions
- partial updates
- missing images/files

### 21) Never trust request input blindly
- Validate everything
- Sanitize where needed
- Authorize sensitive actions
- Use policies/gates for protected flows

---

## Performance Rules

### 22) Respect performance from the start
- Avoid N+1 queries
- Avoid heavy logic in loops
- Paginate large datasets
- Select only needed fields when helpful
- Cache only when justified and maintainable
- Do not prematurely optimize, but do not ignore obvious inefficiency

---

## Code Style Rules

### 23) Keep code consistent
- Follow PSR standards
- Follow Laravel conventions where possible
- Match the existing project style if it is already clean
- Use early returns to reduce nesting
- Prefer guard clauses
- Avoid deeply nested `if` trees
- Keep indentation and structure clean

### 24) Comments
- Do not comment obvious code
- Comment only when the why is important
- Prefer good naming over explanatory comments

---

## Testing and Verification

### 25) Verify every change
For each task:
- verify the code structure follows these rules
- confirm no unrelated areas were damaged
- check that the new behavior matches the request
- if build/test/lint commands exist, run the relevant ones
- confirm touched pages still render correctly

### 26) Definition of done
A task is not done unless:
- logic is in the right layer
- no new fat controller/method/file was introduced
- validation/authorization are in place
- queries are not in controllers/blades
- touched legacy code was improved where reasonable
- code remains maintainable and production-ready

---

## Preferred Folder Conventions

Use these conventions unless the project already has a strong equivalent structure:

- `app/Http/Controllers`
- `app/Http/Requests`
- `app/Services`
- `app/Actions`
- `app/Queries`
- `app/Data` or `app/DTOs`
- `app/Models`
- `app/Policies`
- `app/View/Components`
- `resources/views/components`
- `resources/views/partials`

Suggested examples:
- `app/Services/Courses/CourseEnrollmentService.php`
- `app/Actions/Lessons/ReorderLessonsAction.php`
- `app/Queries/Finance/ManualPaymentRequestsQuery.php`
- `app/Data/Courses/UpdateCourseData.php`

---

## Anti-Patterns That Are Not Allowed
- Fat controllers
- Fat Blade views
- Raw queries in controllers/views
- Massive service classes with unrelated responsibilities
- Massive methods
- Repeated query logic in multiple places
- Copy-paste business logic
- Inline validation everywhere
- Hardcoded admin-manageable content in views
- Manual sorting fields when drag-and-drop is the better admin UX
- Theme-breaking hardcoded UI values without reason

---

## Default Decision Rules
When in doubt:
1. Keep controllers thin
2. Move business logic to a service/action
3. Move reusable filters to model scopes
4. Move complex retrieval to a query class
5. Move validated structured input into a DTO/Data object
6. Keep Blade presentation-only
7. Refactor touched legacy code into alignment
8. Keep files small, methods short, names explicit

---

## Task Execution Workflow
For every task, follow this order:
1. Inspect the current implementation
2. Identify architecture/code-smell issues in the touched area
3. Make the smallest clean change that solves the request
4. Refactor the touched scope to align with these rules
5. Verify behavior and structure before finishing

---

## Final Standard
Every change must leave the codebase:
- cleaner
- more modular
- easier to extend
- easier to review
- safer for long-term maintenance
- more suitable for a premium commercial Laravel product