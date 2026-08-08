# Architecture Notes

## Stack Choice

Chose plain Laravel + Blade + Quill.js (via CDN) over a separate frontend framework (Nuxt/React) to minimize setup complexity within the timebox — no build step, no CORS, single deployable app. SQLite over Postgres/MySQL for zero-config persistence.

## Data Model

- `users` — Laravel's default auth table, seeded with two test accounts
- `documents` — id, user_id (owner), title, content (HTML), timestamps
- `document_user` — pivot table for sharing (many-to-many between documents and users)

## Access Control

Access is checked in the controller: a document is accessible if the current user is the owner OR exists in the document's `sharedWith` pivot relationship. Only the owner can edit or share; shared users get read-only access (enforced both in the UI — hidden toolbar — and server-side — 403 on update attempts by non-owners).

## What I Prioritized

- A working, real editing experience (Quill.js) with genuine formatting, not a plain textarea
- Correct, testable access control for sharing (owner vs. shared distinction enforced server-side, not just hidden in the UI)
- A working deploy over additional polish

## What I Deprioritized

- Full user registration/password reset — used seeded accounts per the assignment's explicit allowance for "mocked auth or a lightweight login flow"
- Real-time collaboration — out of scope per assignment constraints
- Rich file type support for upload — limited to .txt/.md to keep scope tight, stated clearly in the UI and README
- Granular permission levels (e.g. "can comment" vs "can edit") — kept sharing binary (owner vs. viewer) for the timebox

## With Another 2-4 Hours

- Add role-based sharing (viewer vs. editor)
- Support .docx upload via a parser library
- Add document version history
- Polish UI/UX further (loading states, better empty states, mobile responsiveness)