# Feature Specification: Domain Management CRUD

## Context & User Stories
**US2 – List of my domains**
As a logged-in user, I want to see the list of all my technical domains, with for each: the total number of concepts and the number of mastered concepts, to see my progress by domain at a glance.

**US3 – Create a domain**
As a logged-in user, I want to create a domain with a name (e.g., "Laravel ORM", "PHP OOP") and a badge color to visually differentiate it in the list.

**US4 – Edit / Delete a domain**
As a logged-in user, I want to modify the name or color of a domain, or delete it entirely.

## Technical Requirements
- **Model & Migration:** `Domain` entity with fields: `id`, `user_id` (FK), `name` (string), `color` (string 7 chars for hex code, e.g., `#FF5733`), timestamps.
- **Relationships:** `Domain` belongsTo `User`. `Domain` hasMany `Concept`.
- **Validation:** Create `DomainRequest`. Ensure the `color` field strictly validates as a valid hex color code.
- **Performance Optimization:** Use Eloquent's `withCount()` to compute `concepts_count` and `mastered_concepts_count` directly in SQL. 
- **Security:** Enforce strict authorization. A user can only view, edit, or delete domains where `user_id == auth()->id()`.

## 🚫 What I DO NOT want (Negative Constraints)
- DO NOT trigger N+1 queries by looping through domains in Blade to count related concepts or mastered statuses. All aggregations must happen at the database level.
- DO NOT place validation rules inside controller methods. Always use `DomainRequest`.
- DO NOT allow a user to access, edit, or delete another user's domain via URL manipulation (e.g., changing `/domains/5/edit` to an ID they do not own). Always scope queries using `$request->user()->domains()`.