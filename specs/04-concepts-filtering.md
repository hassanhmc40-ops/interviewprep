# Feature Specification: Combined Concept Filtering

## Context & User Story
**US5 & Bonus – List of concepts with combined filtering**
As a logged-in user, I want to see all the concepts of a domain formatted clearly. I need to filter concepts simultaneously by their mastery status AND their difficulty level from the list view to target my preparation efficiently.

## Technical Requirements
- **Implementation:** Extend the concepts index method to accept optional query parameters: `?status=...&difficulty=...`.
- **Query Scopes:** Implement local query scopes on the `Concept` model (e.g., `scopeFilterByStatus($query, $status)` and `scopeFilterByDifficulty($query, $difficulty)`).
- **UI Persistence:** The filter dropdowns/controls in the Blade view must retain their selected state after the page reloads with the applied query parameters.
- **Eager Loading:** Ensure the parent `Domain` and the count of `GeneratedQuestion` sets are eager loaded to maintain zero N+1 queries.

## 🚫 What I DO NOT want (Negative Constraints)
- DO NOT fetch all concepts from the database and filter them in memory using PHP Collections. All filtering must happen strictly inside the SQL query using `WHERE` clauses.
- DO NOT cause an N+1 query regression when displaying UI badges for related domain names or generated question counts.
- DO NOT break the pagination or list rendering if invalid filter values are passed in the URL parameters. Ignore invalid parameters gracefully.