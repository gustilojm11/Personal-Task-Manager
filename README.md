# Tasklyst - Personal Task Manager

**Project Code:** WST21-PM-2026-SF  
**Student Name: GUSTILO, JUAN MIGUEL C. **  
**Course & Year:BSIT-2_SECTION 11**  
**Database Used:** SQLite (Laravel-compatible; can be switched to MySQL in `.env`)

Tasklyst is a focused Laravel application for managing personal tasks through a clean Blade interface.

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status (Pending / Completed)
- Optional task descriptions and due dates
- Progress summary and validation feedback

## Setup

1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Install frontend dependencies and build the assets:
   ```bash
   npm install
   npm run build
   ```
3. Create the environment file and generate an application key:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
4. Configure SQLite by setting these values in `.env`:
   ```env
   DB_CONNECTION=sqlite
   ```
   Laravel will use `database/database.sqlite` by default. Create the empty file if it does not exist.
5. Run the migrations:
   ```bash
   php artisan migrate
   ```
6. Start the application:
   ```bash
   php artisan serve
   ```

Open the local URL shown by Artisan in your browser.
