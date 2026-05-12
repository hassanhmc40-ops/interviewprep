# AGENTS.md — InterviewPrep Laravel Project

**Project Name:** InterviewPrep  
**Type:** Technical Interview Preparation Web Application  
**Framework:** Laravel 10+  
**Duration:** 5 days (Monday 11/05/2026 – Friday 15/05/2026, 13:00)  
**Author/Developer:** Moroccan Web Developer  
**Location:** Casablanca, Morocco  
**Interview Target:** SaaS Startup — Laravel Backend Position  
**Timeline:** Interview in 10 days

---

## 📋 Project Overview

InterviewPrep is a personal web application designed to help a developer structure and systematically track technical interview preparation. The application allows users to:
- Organize knowledge by technical domains (OOP, Laravel, MySQL, REST API, etc.)
- Create and manage revision notes for each concept
- Track mastery levels (To Review / In Progress / Mastered)
- Generate realistic technical interview questions via Groq API
- View generation history and track progress through a dashboard

**Core Problem:** The developer has scattered knowledge without clear structure, unable to definitively state what is truly mastered vs. what needs review.

**Core Solution:** A structured, organized preparation tool with AI-generated practice questions.

---

## 🎯 User Stories & Requirements

### 1. Authentication 🔐

**US1 – Registration / Login / Logout**
- Users can create accounts, authenticate, and log out
- Standard Laravel authentication (email/password)
- Implement with Laravel Breeze or custom auth controller

### 2. Domain Management 🗂️

**US2 – List of My Domains**
- Display all domains with:
  - Total number of concepts per domain
  - Number of mastered concepts per domain
  - Progress visualization at a glance
- Query optimization: use eager loading to avoid N+1 queries

**US3 – Create a Domain**
- Form to create new domain with:
  - Name field (e.g., "Laravel ORM", "PHP OOP", "MySQL")
  - Badge color selection (for visual differentiation)
- Store in `domains` table with `user_id` FK
- Validate: name required, max 255 characters

**US4 – Edit / Delete a Domain**
- Edit domain name and color
- Delete domain (with soft delete bonus feature)
- Cascade or cascade soft-delete concepts attached to domain

### 3. Concept Management ✍️

**US5 – List of Concepts for a Domain**
- Display all concepts with:
  - Title
  - Difficulty level (formatted: Junior / Mid / Senior)
  - Mastery status (formatted: To Review / In Progress / Mastered)
- Filter by status
- Bonus: Filter by difficulty level AND status simultaneously
- Eager load related data (use `with()` to prevent N+1)

**US6 – Create a Concept**
- Form fields:
  - Title: name of technical concept (e.g., "Eloquent N+1 Problem")
  - Explanation: detailed note in developer's own words
  - Difficulty level: junior / mid / senior (enum or select)
  - Initial status: defaults to "to_review"
- Validation via Form Request class
- Associate with domain_id and user_id

**US7 – View Concept Details**
- Display:
  - Title
  - Full explanation
  - Difficulty level (formatted)
  - Status (formatted)
  - Generated interview questions (from generations table)
  - Generation history (all past AI generations)

**US8 – Edit a Concept**
- Modify any field: title, explanation, difficulty, status
- Use Form Request for validation
- Update timestamps

**US9 – Quickly Change Status**
- AJAX or inline toggle from concept list
- Change status: To Review → In Progress → Mastered → To Review (cycle)
- Immediate visual feedback without page reload

**US10 – Delete a Concept**
- Delete concept and all associated generated_questions
- Bonus: Implement soft delete with restore option via "Archived" page

### 4. AI Generation of Interview Questions

**US11 – Generate Interview Questions**
- Button on concept detail page: "Generate Interview Questions"
- Call Groq API with concept title + explanation
- Request 5 realistic technical interview questions
- Display questions immediately after generation
- Save to `generated_questions` table before display

**US12 – View Generation History**
- Show all past generations for a concept
- Each generation displays:
  - Generation timestamp
  - 5 questions generated
  - Option to delete this generation

**US13 – Delete a Generation**
- Delete a specific set of generated questions
- Keep concept intact
- Remove from generated_questions table

**US14 – Soft Delete (Bonus)**
- Soft delete concepts instead of permanent deletion
- Archive concepts and allow restoration
- Add `deleted_at` timestamp to concepts table

### 5. Bonus Features

**Progress Dashboard**
- Home page with statistics:
  - Total concepts by status (pie/bar chart)
  - Best-mastered domain
  - Domain most in need of revision
  - Percentage completion by domain
- Use aggregation queries for performance

**Soft Deletes for Concepts**
- Archive instead of permanently delete
- "Archived" page for restoration
- Add `SoftDeletes` trait to Concept model

**Combined Filters**
- Filter concepts by status AND difficulty level simultaneously
- Queryable filters on concept list page

---

## 🏗️ Technical Architecture

### Database Schema (MCD → MLD)

#### Entities (MCD - Conceptual Data Model)
```
User
├── id (PK)
├── name
├── email
├── password
└── timestamps

Domain
├── id (PK)
├── user_id (FK → User)
├── name
├── color
├── timestamps
└── soft_deletes (optional)

Concept
├── id (PK)
├── domain_id (FK → Domain)
├── user_id (FK → User)
├── title
├── explanation
├── difficulty_level
├── status
├── timestamps
└── soft_deletes (optional)

GeneratedQuestion
├── id (PK)
├── concept_id (FK → Concept)
├── question
├── generated_at
└── deleted_at (optional)
```

#### Tables (MLD - Logical Data Model)
```sql
users
├── id: BIGINT UNSIGNED PRIMARY KEY
├── name: VARCHAR(255)
├── email: VARCHAR(255) UNIQUE
├── email_verified_at: TIMESTAMP NULL
├── password: VARCHAR(255)
├── remember_token: VARCHAR(100) NULL
├── created_at: TIMESTAMP
└── updated_at: TIMESTAMP

domains
├── id: BIGINT UNSIGNED PRIMARY KEY
├── user_id: BIGINT UNSIGNED FK(users.id) ON DELETE CASCADE
├── name: VARCHAR(255)
├── color: VARCHAR(7) [hex color code, e.g., #FF5733]
├── created_at: TIMESTAMP
├── updated_at: TIMESTAMP
└── deleted_at: TIMESTAMP NULL [optional for soft deletes]

concepts
├── id: BIGINT UNSIGNED PRIMARY KEY
├── domain_id: BIGINT UNSIGNED FK(domains.id) ON DELETE CASCADE
├── user_id: BIGINT UNSIGNED FK(users.id) ON DELETE CASCADE
├── title: VARCHAR(255)
├── explanation: LONGTEXT
├── difficulty_level: ENUM('junior', 'mid', 'senior')
├── status: ENUM('to_review', 'in_progress', 'mastered') DEFAULT 'to_review'
├── created_at: TIMESTAMP
├── updated_at: TIMESTAMP
└── deleted_at: TIMESTAMP NULL [optional for soft deletes]

generated_questions
├── id: BIGINT UNSIGNED PRIMARY KEY
├── concept_id: BIGINT UNSIGNED FK(concepts.id) ON DELETE CASCADE
├── question: LONGTEXT
├── generated_at: TIMESTAMP
└── deleted_at: TIMESTAMP NULL [optional for soft deletes]
```

### Eloquent Relationships

```php
// User Model
User
  ├── has Many → Domains
  └── has Many → Concepts

// Domain Model
Domain
  ├── belongs To → User
  └── has Many → Concepts

// Concept Model
Concept
  ├── belongs To → User
  ├── belongs To → Domain
  └── has Many → GeneratedQuestions

// GeneratedQuestion Model
GeneratedQuestion
  └── belongs To → Concept
```

### Eloquent Accessors (Required)

```php
// Concept Model Accessors
statusLabel()     // 'to_review' → 'To Review', 'in_progress' → 'In Progress', 'mastered' → 'Mastered'
difficultyLabel() // 'junior' → 'Junior', 'mid' → 'Mid', 'senior' → 'Senior'
```

### Form Requests (Validation)

- `StoreDomainRequest` — validate domain creation
- `UpdateDomainRequest` — validate domain updates
- `StoreConceptRequest` — validate concept creation with title, explanation, difficulty, status
- `UpdateConceptRequest` — validate concept updates

---

## 🤖 AI-Assisted Workflow Requirements

### 1. AGENTS.md File
- **Status:** Must be committed as first commit on Day 1
- **Content:** Complete project information for AI agents
- **Location:** Project root `/AGENTS.md`
- **Purpose:** Serve as single source of truth for all project context

### 2. specs/ Folder Structure
- **Location:** `/specs/` directory in project root
- **Content:** One `.md` file per feature built with coding agent
- **Minimum Required:**
  - `specs/01-domains-crud.md` — Domain creation, read, update, delete
  - `specs/02-concepts-crud.md` — Concept management with status filtering
  - `specs/03-ai-generation.md` — Groq API integration and question generation
- **Additional Optional:**
  - `specs/04-dashboard.md` — Progress statistics
  - `specs/05-soft-deletes.md` — Archive and restore functionality

### 3. Coding Agent Usage

**Recommended Agent:** OpenCode (opencode.ai)
- Free tier available
- Terminal-based, open-source
- Works well with Laravel projects

**Alternative Agents:**
- Claude Code (claude.ai/code)
- Gemini CLI (github.com/google-gemini/gemini-cli)
- GitHub Copilot CLI

**Workflow for Each Feature:**
1. **Plan Mode (First):** Agent analyzes requirements, suggests architecture, identifies edge cases
2. **Build Mode (Second):** Agent generates actual code based on plan
3. **Manual Review:** Developer modifies, refines, fixes hallucinations
4. **Commit:** Clear message stating AI usage (e.g., "feat: add domain CRUD with OpenCode Plan + manual refinements")

### 4. Commit Message Standards

**Format:**
```
<type>(<scope>): <subject>

<body with AI usage details>

AI Tool: <Agent Name> (Plan/Build)
Modified: <what was changed manually>
```

**Examples:**
```
feat(domains): implement domain CRUD operations

- Create, read, update, delete domains
- Add color badge selection
- Optimize queries with eager loading

AI Tool: OpenCode (Plan + Build)
Modified: Fixed N+1 query in domains list, added ->with('concepts')

---

feat(concepts): add concept management with quick status change

- Full CRUD for concepts
- Inline status toggle via AJAX
- Filter by difficulty and status

AI Tool: Claude Code (Build)
Modified: Refactored AJAX endpoint, improved error handling

---

feat(ai): integrate Groq API for interview question generation

- Generate 5 questions from concept title + explanation
- Save to database before display
- Add generation history view

AI Tool: OpenCode (Plan + Build)
Modified: Added error handling for API timeouts, manual retry logic
```

### 5. specs/ File Structure Template

Each spec file should follow this structure:

```markdown
# Feature Name — Specification

## Overview
Brief description of what this feature does and why it's needed.

## User Stories
- US#: Description

## Requirements
- Functional requirements
- Technical requirements
- Database changes

## Architecture
- Models/Tables involved
- Relationships
- Key methods/endpoints

## Implementation Steps
1. Step 1
2. Step 2
...

## Validation Rules
- Input validation
- Business logic validation

## Edge Cases
- What happens if...
- Error scenarios

## Testing Checklist
- [ ] Unit tests
- [ ] Feature tests
- [ ] Integration tests

## Performance Considerations
- N+1 queries prevented
- Indexing strategy
- Query optimization

## Notes
- Additional context
- Known limitations
- Future improvements
```

---

## 🔌 AI API Integration

### Groq API Setup (Recommended)

**API Provider:** Groq (console.groq.com)
- **Cost:** Free tier (no credit card required)
- **Speed:** Ultra-fast responses
- **Rate Limit:** Generous free tier

**API Endpoint:**
```
POST https://api.groq.com/api/v1/chat/completions
```

**Headers:**
```
Authorization: Bearer {GROQ_API_KEY}
Content-Type: application/json
```

**Request Payload:**
```json
{
  "model": "mixtral-8x7b-32768",
  "messages": [
    {
      "role": "user",
      "content": "Generate 5 realistic technical interview questions about: [CONCEPT_TITLE]\n\nExplanation: [CONCEPT_EXPLANATION]\n\nReturn ONLY a JSON array with 5 questions, no preamble."
    }
  ],
  "temperature": 0.7,
  "max_tokens": 2048
}
```

**Response Format:**
```json
{
  "choices": [
    {
      "message": {
        "content": "[JSON array with 5 interview questions]"
      }
    }
  ]
}
```

### Alternative AI APIs

**Google Gemini API**
- Free tier at ai.google.dev
- endpoint: `https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent`

**Mistral API**
- Free tier at console.mistral.ai
- Endpoint: `https://api.mistral.ai/v1/chat/completions`

**Together AI**
- Free credits at together.ai
- Similar OpenAI-compatible endpoint

### Laravel Http:: Implementation

**CRITICAL RULES:**
1. Use only Laravel's native `Http::` facade — NO external AI packages
2. Store API key in `.env` file ONLY — NEVER hardcode in Laravel code
3. NEVER commit `.env` file or expose API keys in repository
4. Implement error handling for failed API responses
5. Save result to database BEFORE displaying to user
6. Handle timeouts gracefully with user-friendly error messages

**Implementation Pattern:**

```php
// In controller or service class
use Illuminate\Support\Facades\Http;

public function generateQuestions(Concept $concept)
{
    try {
        $response = Http::timeout(30)
            ->withToken(config('services.groq.api_key'))
            ->post('https://api.groq.com/api/v1/chat/completions', [
                'model' => 'mixtral-8x7b-32768',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Generate 5 technical interview questions about:\n\nTitle: {$concept->title}\n\nExplanation: {$concept->explanation}\n\nReturn ONLY a JSON array."
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2048
            ]);

        if ($response->failed()) {
            throw new \Exception('API request failed');
        }

        $data = $response->json();
        $questions = json_decode($data['choices'][0]['message']['content'], true);

        // Save to database
        foreach ($questions as $question) {
            GeneratedQuestion::create([
                'concept_id' => $concept->id,
                'question' => $question,
                'generated_at' => now()
            ]);
        }

        return response()->json(['success' => true, 'questions' => $questions]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate questions. Please try again.'
        ], 500);
    }
}
```

**Environment Configuration (.env):**
```
GROQ_API_KEY=your_api_key_here
```

**Config File (config/services.php):**
```php
'groq' => [
    'api_key' => env('GROQ_API_KEY'),
]
```

---

## 📊 Performance Requirements

### Zero N+1 Verification
- Use Laravel Debugbar to verify no N+1 queries
- Apply eager loading with `with()` on all domain and concept queries
- Example:
  ```php
  Domain::with('concepts', 'concepts.generatedQuestions')
      ->where('user_id', auth()->id())
      ->get();
  ```

### Eloquent Best Practices
- Define relationships correctly (hasMany, belongsTo, etc.)
- Use relationship accessors for formatted display
- Implement soft deletes properly with `withTrashed()`, `onlyTrashed()`

### Database Indexing
- Index `user_id` on domains, concepts
- Index `domain_id` on concepts
- Index `concept_id` on generated_questions
- Consider composite indexes for common filter queries

---

## 📁 Project Structure

```
InterviewPrep/
├── AGENTS.md ← You are here (First commit, Day 1)
├── README.md
├── .env.example
├── .env ← API key stored here, NEVER committed
├── .gitignore ← Must exclude .env and /node_modules
│
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Domain.php
│   │   ├── Concept.php
│   │   └── GeneratedQuestion.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DomainController.php
│   │   │   ├── ConceptController.php
│   │   │   └── AIController.php (or GeneratedQuestionController.php)
│   │   └── Requests/
│   │       ├── StoreDomainRequest.php
│   │       ├── UpdateDomainRequest.php
│   │       ├── StoreConceptRequest.php
│   │       └── UpdateConceptRequest.php
│   │
│   └── Services/ (Optional)
│       └── AIService.php (Encapsulates Groq API logic)
│
├── database/
│   └── migrations/
│       ├── 2024_XX_XX_create_users_table.php
│       ├── 2024_XX_XX_create_domains_table.php
│       ├── 2024_XX_XX_create_concepts_table.php
│       └── 2024_XX_XX_create_generated_questions_table.php
│
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── domains/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── concepts/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── dashboard.blade.php
│
├── routes/
│   ├── web.php
│   └── api.php (Optional, for AJAX endpoints)
│
├── specs/ ← Committed to repo
│   ├── 01-domains-crud.md
│   ├── 02-concepts-crud.md
│   ├── 03-ai-generation.md
│   ├── 04-dashboard.md (Optional)
│   └── 05-soft-deletes.md (Optional)
│
└── tests/ (Optional but recommended)
    ├── Unit/
    └── Feature/
```

---

## 🔄 Development Workflow (5 Days)

### Day 1: Monday 11/05 (Start 10:00)
- **Deadline:** MCD & MLD submitted for approval
- **Commits:**
  1. Initial Laravel setup with Breeze authentication
  2. AGENTS.md (THIS FILE)
  3. Database migrations for User, Domain, Concept, GeneratedQuestion
  4. Model relationships defined

### Days 2-3: Tuesday & Wednesday
- **Focus:** Feature development with agents
- **Branch:** `feature/domains-crud`
  - Implement US2, US3, US4
  - Create spec: `specs/01-domains-crud.md`
- **Branch:** `feature/concepts-crud`
  - Implement US5, US6, US7, US8, US9, US10
  - Create spec: `specs/02-concepts-crud.md`
- **Daily commits:** At least 1 per feature per day

### Days 4: Thursday
- **Focus:** AI Integration
- **Branch:** `feature/ai-generation`
  - Implement US11, US12, US13
  - Create spec: `specs/03-ai-generation.md`
  - Test Groq API integration
  - Debug N+1 queries with Debugbar

### Day 5: Friday (Until 13:00)
- **Morning:** Bonus features (dashboard, soft deletes, combined filters)
- **Late Morning:** Testing, bug fixes, performance optimization
- **Noon (Before 13:00):** Final commits, repository push, Jira board sync

---

## ✅ Assessment Checklist

### Code Quality (30%)
- [ ] Eloquent relationships defined correctly (3+ levels: User → Domain → Concept → GeneratedQuestion)
- [ ] Form Request classes for ALL validations (4 required)
- [ ] statusLabel() and difficultyLabel() accessors working in views
- [ ] statusLabel() and difficultyLabel() working in AI responses
- [ ] Http:: API call via native Laravel only (no external packages)
- [ ] API result saved to database before display
- [ ] Zero N+1 verified with Debugbar (especially domains list + concepts count)

### Features (25%)
- [ ] Domain CRUD fully functional (create, read, update, delete)
- [ ] Concept CRUD fully functional with all operations
- [ ] Quick status change from concept list (AJAX preferred)
- [ ] AI generation working end-to-end (5 questions, saved to DB)
- [ ] Generation history visible and deletable
- [ ] Filtering by status working (bonus: difficulty + status combined)

### AI-Assisted Workflow (25%)
- [ ] AGENTS.md present in root, complete with all sections
- [ ] At least 3 spec files in `/specs/` (domains, concepts, ai-generation minimum)
- [ ] Minimum 15 commits with clear AI usage statements
- [ ] Daily commits visible in commit history
- [ ] Feature branches exist (feature/domains-crud, feature/concepts-crud, feature/ai-generation)
- [ ] Ability to explain during assessment what agent generated vs. manual modifications

### Presentation (20%)
- [ ] Mandatory structure followed (Title, Context, MCD, MLD, Stack, AI Workflow, Feature, Demo, Reflection, Conclusion)
- [ ] MCD slide present with clear entities
- [ ] MLD slide present with full table schemas
- [ ] "AI-Assisted Workflow" slide with actual screenshots of specs and commits
- [ ] All slides under 30 words
- [ ] Minimum 1 visual per slide (diagram, screenshot, code, chart)
- [ ] Minimum font size 24px throughout
- [ ] Slide numbers visible on each slide

### Deliverables
- [ ] GitHub repository with 15+ commits, clear messages
- [ ] Daily commits visible from Monday to Friday
- [ ] Feature branches properly named
- [ ] Jira board shared (abderahmane.merradou@gmail.com) by Monday 13:00
- [ ] All user stories converted to tickets
- [ ] MCD submitted Monday (approved before coding)
- [ ] MLD submitted Monday (approved before coding)
- [ ] README.md complete with setup instructions
- [ ] .gitignore properly configured (.env excluded)

---

## 🚀 Quick Start Commands

```bash
# Clone and setup
git clone <repo-url>
cd InterviewPrep
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Run server
php artisan serve

# Run tests
php artisan test

# Check for N+1 with Debugbar
# Enable in .env: DEBUGBAR_ENABLED=true
# Access at http://localhost:8000/debugbar

# AI Agent Setup (Example: OpenCode)
opencode plan "Build domain CRUD feature for Laravel app"
opencode build "Create DomainController with store, update, destroy methods"
```

---

## 📞 Support & Debugging

### Common Issues

**API Key Not Working:**
- Verify `.env` has correct `GROQ_API_KEY`
- Check API key format (no extra spaces)
- Test API key with curl before integrating

**N+1 Queries Detected:**
```php
// Bad
Domain::all(); // Then access concepts in loop

// Good
Domain::with('concepts', 'concepts.generatedQuestions')->get();
```

**AJAX Status Change Not Working:**
- Verify CSRF token in form/request
- Check browser console for JavaScript errors
- Ensure route is post/patch, not get

**AI Generation Timeout:**
```php
// Set appropriate timeout
Http::timeout(30)->post(...)
```

---

## 📝 Notes for AI Agents

**When generating code:**
1. Always ask clarifying questions if requirements are ambiguous
2. Provide both the problem analysis and solution
3. Include error handling and validation
4. Generate Form Request classes for all input
5. Write efficient queries (eager loading, pagination)
6. Comment complex logic
7. Follow Laravel conventions and naming standards

**When the agent needs context:**
- Reference AGENTS.md sections by title
- Ask for user story number (US#) when specific feature is unclear
- Request example of expected behavior if unsure
- Ask for clarification on database relationships

**Common hallucinations to avoid:**
- Don't use external AI packages (use only Http:: facade)
- Don't commit API keys or .env files
- Don't create N+1 query patterns
- Don't skip Form Request validation classes
- Don't forget accessors for formatted label display
- Don't assume specific API response structure without checking docs

---

## 📚 Additional Resources

**Laravel Documentation:**
- Eloquent ORM: https://laravel.com/docs/10.x/eloquent
- HTTP Client: https://laravel.com/docs/10.x/http-client
- Form Requests: https://laravel.com/docs/10.x/validation#form-request-validation
- Accessors: https://laravel.com/docs/10.x/eloquent-mutators#accessors-serialization

**Groq API:**
- Console: https://console.groq.com
- Documentation: https://console.groq.com/docs
- Models Available: mixtral-8x7b-32768 (recommended for speed)

**Coding Agents:**
- OpenCode: https://opencode.ai
- Claude Code: https://claude.ai/code
- GitHub Copilot: https://github.com/features/copilot

---

**Document Version:** 1.0  
**Last Updated:** May 11, 2026  
**Prepared for:** Moroccan Web Developer, Casablanca  
**Next Review:** After Day 1 completion

---

*This AGENTS.md file serves as the master reference for all project participants and AI agents. Update this file if requirements change significantly during development.*