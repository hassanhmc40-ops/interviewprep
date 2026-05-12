# Feature Specification: Progress Analytics Dashboard

## Context & User Story
**Bonus – Progress Dashboard**
As a logged-in user, I want a landing dashboard showing key statistics immediately upon logging in: the total number of concepts grouped by status, my best-mastered technical domain, and the domain most in need of revision, so I know exactly where to focus my efforts.

## Technical Requirements
- **Controller:** Create a dedicated `DashboardController` handling the `/dashboard` route.
- **Aggregations:** Calculate the following metrics efficiently using Eloquent/SQL:
  1. Total concepts count grouped by status (`to_review`, `in_progress`, `mastered`).
  2. Best-mastered domain (domain with the highest percentage of mastered concepts).
  3. Revision priority domain (domain with the lowest percentage of mastered concepts, or highest count of `to_review` concepts).
- **UI:** Render these metrics cleanly using visually distinct cards or simple visual charts in Blade.

## 🚫 What I DO NOT want (Negative Constraints)
- DO NOT execute raw `Domain::all()` or `Concept::all()` queries to load the entire database into PHP memory for computation. You must use database-level aggregations (`groupBy()`, `selectRaw()`, `withCount()`).
- DO NOT display statistics from other users. All metrics must be strictly isolated to the authenticated user's workspace.
- DO NOT execute separate, heavy SQL queries inside loops. Optimize the database calls to fetch dashboard metrics with minimal overhead.