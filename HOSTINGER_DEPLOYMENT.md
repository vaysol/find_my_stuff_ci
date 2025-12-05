# 🚀 Hostinger Deployment Guide

This guide will walk you through deploying your CodeIgniter 4 application to Hostinger Shared Hosting.

## Prerequisites
- A Hostinger hosting account
- Access to hPanel
- Your project files ready on your computer

---

## Step 1: Prepare Your Project Files

1.  **Clean Up**:
    - Delete `writable/cache/*` content (keep the folder).
    - Delete `writable/logs/*` content (keep the folder).
    - Delete `writable/session/*` content (keep the folder).
    - Delete `.git` folder (optional, reduces size).

2.  **Zip Your Project**:
    - Select all files and folders in your project root (`find_my_stuff_ci`).
    - Compress them into a single file named `project.zip`.

---

## Step 2: Database Setup (hPanel)

1.  Log in to **Hostinger hPanel**.
2.  Go to **Databases** -> **Management**.
3.  Create a New Database:
    - **Database Name**: e.g., `u123456789_findstuff` (Hostinger adds a prefix)
    - **Database User**: e.g., `u123456789_admin`
    - **Password**: Create a strong password (and save it!)
4.  Click **Create**.
5.  **Import Schema**:
    - Click **Enter phpMyAdmin** next to your new database.
    - Click **Import** tab.
    - Upload your `database_schema.sql` file (if you deleted it, export it from your local MAMP phpMyAdmin first).
    - Click **Go**.

---

## Step 3: Upload Files

1.  Go to **Files** -> **File Manager**.
2.  Navigate to `public_html`.
3.  **Upload** your `project.zip` file here.
4.  **Extract** the zip file:
    - Right-click `project.zip` -> **Extract**.
    - Extract to `.` (current directory) or a folder name.

### Structure Adjustment (Recommended for Security)
Ideally, your application code (`app`, `system`, etc.) should be *outside* `public_html`.

**Option A: Secure Structure (Recommended)**
1.  Create a folder named `ci4_core` at the same level as `public_html` (go up one level).
2.  Move `app`, `system`, `writable`, `.env`, `spark`, `composer.json` into `ci4_core`.
3.  Keep only the contents of `public` inside `public_html`.
4.  Edit `public_html/index.php`:
    ```php
    require FCPATH . '../ci4_core/app/Config/Paths.php';
    ```

**Option B: Simple Structure (Easier)**
1.  Keep everything in `public_html`.
2.  Move the contents of `public` folder to the root of `public_html`.
3.  Edit `index.php` (now in `public_html`):
    ```php
    require FCPATH . 'app/Config/Paths.php';
    ```
4.  **Crucial**: Ensure `.htaccess` blocks access to `app`, `writable`, etc.

---

## Step 4: Configuration

1.  **Edit `.env` file** (in File Manager):
    - Rename `env` to `.env` if needed.
    - Update settings:
      ```env
      CI_ENVIRONMENT = production
      app.baseURL = 'https://your-domain.com/'
      
      database.default.hostname = localhost
      database.default.database = u123456789_findstuff  <-- Use Hostinger DB Name
      database.default.username = u123456789_admin      <-- Use Hostinger DB User
      database.default.password = your_strong_password
      database.default.DBDriver = MySQLi
      ```

2.  **Permissions**:
    - Right-click `writable` folder -> **Permissions**.
    - Set to `755` (or `777` if you have issues, but `755` is safer).
    - Ensure `Recursive` is checked.

---

## Step 5: Final Checks

1.  Visit your website URL.
2.  If you see a "404 File Not Found" or "403 Forbidden":
    - Check your `.htaccess` file.
    - Ensure `index.php` is in the right place.
3.  If you see "Whoops! We seem to have hit a snag":
    - This is the production error page (good!).
    - Check `writable/logs` for actual error details.

---

## Troubleshooting

- **Database Connection Error**: Double-check the database name and user prefix (Hostinger always adds `u123..._`).
- **Images/CSS not loading**: Check `app.baseURL` in `.env`. Ensure it ends with a `/`.
- **500 Internal Server Error**: Check `.htaccess` syntax. Hostinger uses Apache, so standard CI4 `.htaccess` works well.
