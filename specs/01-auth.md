# Feature Specification: Authentication & User Workspace

## Context & User Story
**US1 – Registration / Login / Logout**
As a user, I want to create my account, log in, and log out securely so that my interview preparation data is entirely private and isolated from other users.

## Technical Requirements
- **Implementation:** Use Laravel Breeze (Blade stack) or a clean manual implementation using Laravel's native authentication tools.
- **Validations:** Create `RegisterRequest` and `LoginRequest` validation classes.
- **Fields:** `name`, `email` (unique, valid email format), `password` (minimum 8 characters, confirmed).
- **Redirection:** Authenticated users must be redirected to the dashboard (`/dashboard`). Unauthenticated users attempting to access protected routes must be redirected to the login page.

## 🚫 What I DO NOT want (Negative Constraints)
- DO NOT validate incoming request data directly inside the authentication controllers.
- DO NOT allow unauthenticated access to any route except the landing page, login, and registration views.
- DO NOT expose password hashes or sensitive tokens anywhere in the frontend or API responses.
- DO NOT use external authentication packages (e.g., Fortify, Jetstream) that add unnecessary bloat. Keep the authentication layer lightweight and native.