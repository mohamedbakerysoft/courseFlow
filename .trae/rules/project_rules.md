### Rule:
If logic exists in an Action → it MUST be tested.

---

## 🚫 ABSOLUTE RULE
- Never move logic into controllers just to avoid testing.
- If logic is complex → move it to an Action and test it.

---

## 3️⃣ BROWSER TESTS (LARAVEL DUSK — REQUIRED)

### Purpose:
Browser tests provide **visual confirmation** that the system works end-to-end.

### Browser tests MUST be used to:
- Open the real browser (Chrome)
- Perform login
- Navigate dashboards
- Purchase courses
- Access lessons
- Verify UI behavior

### When to use Browser Tests:
- After completing a feature
- After fixing a bug
- Before marking a task as “done”

### Rule:
After finishing a feature:
1. Run Feature & Action tests
2. Then run Browser tests
3. Watch the browser execution visually
4. Confirm behavior manually

---

### Browser Tests Scope (IMPORTANT)
- Browser tests should be **few but meaningful**
- Do NOT test every UI detail
- Focus on:
  - Login flow
  - Purchase flow
  - Course access
  - Lesson playback
  - Dark/Light mode toggle
  - RTL layout check (Arabic)

### Folder:
---

## 🚨 BLOCKING RULE (VERY IMPORTANT)

- If ANY test fails:
  - The AI must STOP
  - Fix the issue
  - Re-run tests
- **DO NOT add new features until tests pass.**
- No partial fixes.
- No TODOs.

---

## LARAVEL ARCHITECTURE RULES

### Controllers
- Controllers must be thin.
- Controllers may ONLY:
  - Receive requests
  - Call Actions or Services
  - Return responses
- No business logic in controllers.
- If a controller method exceeds **40 lines**, refactor.

---

### Services & Actions
- All business logic MUST live in:
  - `App\Actions`
  - or `App\Services`
- Each Action:
  - Does one thing
  - Has a clear name
  - Is fully tested

---

### Models
- Models must remain lean.
- Allowed:
  - Relationships
  - Accessors / Mutators
  - Query scopes
- Forbidden:
  - Business logic
  - External service calls

---

## REQUEST VALIDATION

- Never validate inside controllers.
- Always use Form Request classes.

---

## BLADE & FRONTEND RULES

### Blade
- Blade files must be small and reusable.
- Use Blade components.
- No raw PHP.
- No logic-heavy views.

### Alpine.js
- UI behavior only (modals, toggles, tabs).
- No business logic.
- No API calls.

### Tailwind
- Use utility classes.
- Refactor repeated UI into components.

---

## PAYMENTS

- Payment logic must be isolated.
- Separate services for:
  - Stripe
  - PayPal
  - Manual payments
- Payment success MUST be verified.
- All payment flows MUST be tested.

---

## MULTI-LANGUAGE & RTL

- Use Laravel localization files.
- No hard-coded strings.
- RTL must be respected.
- Arabic must be tested via Browser Tests.

---

## STRICT NO-GO LIST ❌

You must NEVER:
- Skip tests
- Comment out failing tests
- Add features on top of failing tests
- Write large files
- Build custom auth
- Over-engineer
- Hack UI to “just work”

---

## FINAL GOLDEN RULE

> **If tests fail, nothing else matters. Fix tests first.**

---

## EXECUTION MODE

When generating code:
1. Explain briefly what is being built
2. Write clean, minimal code
3. Write or update tests
4. Run tests
5. Fix failures
6. Only then proceed

Failure to follow this process is not acceptable.

# LOCAL DEVELOPMENT & INSTALLATION RULES

This project MUST be easy to run:
- Locally for the author
- Locally for buyers
- On shared hosting environments

The system must NOT depend on complex or uncommon setups.

---

## DEVELOPMENT ENVIRONMENT (AUTHOR)

### REQUIRED
- Laravel Sail (Docker) MUST be used for development.
- This guarantees consistent environment and fewer bugs.

### Rules
- All development must run inside Sail.
- PHP version must match production requirements.
- Database must be MySQL (inside Sail).

### Allowed Services
- MySQL
- Redis (optional)
- Mailpit (for email testing)

### Development Commands
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed