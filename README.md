# Lightweight Collaborative Document Editor

A minimal Google Docs-style editor built with Laravel, Blade, and Quill.js.

## Setup & Run Locally

1. Clone the repo and install PHP dependencies:
   composer install

2. Copy environment file and generate app key:
   cp .env.example .env
   php artisan key:generate

3. Set up SQLite database:
   touch database/database.sqlite

4. In `.env`, set:
   DB_CONNECTION=sqlite

5. Run migrations and seed test users:
   php artisan migrate --seed

6. Serve the app:
   php artisan serve

7. Visit http://127.0.0.1:8000

## Test Accounts

- test@example.com / password (has an owned document)
- marge.mosciski@example.org / password (has a document shared with them)

## Features

- Create, rename, and edit documents with rich text (bold, italic, underline, headings, bulleted/numbered lists) via Quill.js
- Autosave, 800ms after typing stops
- Upload a .txt or .md file to create a new document from its content
- Share a document with another user by email; shared users can view but not edit
- Documents persist in SQLite; content stored as HTML

## Supported File Types for Upload

Only .txt and .md files are supported (max 2MB).

## Running Tests

php artisan test