# Feature Specification: Concept Management CRUD & Soft Deletes

## Context & User Stories
**US6 – Create a concept**
As a logged-in user, I want to create a concept in a domain with a title, an explanation in my own words, a difficulty level (Junior/Mid/Senior), and an initial status (To review by default).

**US7 – View concept details**
As a logged-in user, I want to open a concept and see its title, explanation, level, status, and associated generated questions.

**US8 – Edit a concept**
As a logged-in user, I want to modify the title, explanation, difficulty, or status of a concept.

**US9 – Quickly change status**
As a logged-in user, I want to change the status of a concept directly from the concept list without opening the full edit form.

**US10 & US14 – Delete & Soft Delete a concept**
As a logged-in user, I want to archive (soft delete) a concept instead of permanently deleting it, with an "Archived" page available for restoration.

## Technical Requirements
- **Model & Migration:** `Concept` entity with fields: `id`, `domain_id` (FK), `title` (string), `explanation` (text), `difficulty` (enum), `status` (enum), timestamps, `deleted_at` (soft deletes).
- **Enums:** Create dedicated PHP Enum classes for `DifficultyEnum` (`junior`, `mid`, `senior`) and `StatusEnum` (`to_review`, `in_progress`, `mastered`).
- **Accessors:** Implement `statusLabel()` and `difficultyLabel()` accessors on the model to output clean UI strings (e.g., "To Review", "In Progress").
- **Quick Toggle (US9):** Implement an Alpine.js or native AJAX fetch call to toggle the status directly from the list view without a full page reload.
- **Soft Deletes:** Implement an "Archived Concepts" view that lists soft-deleted records, providing a "Restore" action.

## 🚫 What I DO NOT want (Negative Constraints)
- DO NOT hardcode raw strings for status and difficulty validations or database assignments. You must use the PHP Enums.
- DO NOT perform a full page reload when the user triggers the "Quick Status Change" (US9). It must be handled asynchronously via a lightweight fetch/AJAX request.
- DO NOT permanently delete records from the database when the user clicks delete. You must strictly enforce Eloquent's `SoftDeletes` trait.
- DO NOT allow users to attach concepts to domains they do not own. Validate domain ownership during creation.