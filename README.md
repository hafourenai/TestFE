# AttendEase

> **Bahasa Indonesia?** Lihat [README.id.md](README.id.md) — Dokumentasi dalam Bahasa Indonesia.

A simple employee attendance management system with full CRUD operations, built with PHP, MySQL, and the Backstrap template.

## Tech Stack

| Layer        | Technology                                     |
|-------------|------------------------------------------------|
| Backend     | PHP 8.0 (Native)                               |
| Database    | MySQL / MariaDB via MySQLi                     |
| Frontend    | HTML5, CSS3, Bootstrap 4.6 + CoreUI 2         |
| Template    | Backstrap (`@digitallyhappy/backstrap`)       |
| Icons       | Font Awesome 4.7 + CoreUI Icons               |
| Font        | Source Sans Pro (Google Fonts)                |

## Project Structure

```
Test FrontEnd/
├── assets/
│   ├── css/
│   │   └── custom.css             # App-specific custom styles
│   └── backstrap/                 # Real Backstrap template assets
│       ├── css/
│       ├── js/
│       ├── img/
│       └── vendors/
├── config/
│   └── database.php           # MySQL connection config (port 8111)
├── database/
│   └── attendance.sql         # Database schema + seed
├── includes/
│   ├── header.php             # <head>, Backstrap CSS + vendors
│   ├── navbar.php             # CoreUI .app-header (top nav)
│   ├── sidebar.php            # CoreUI .sidebar + .main wrapper
│   └── footer.php             # Mobile bottom nav, Backstrap JS
├── pages/
│   ├── dashboard.php          # Overview with stat cards + recent records
│   ├── attendance-list.php    # List with search, sort, pagination
│   ├── attendance-create.php  # Add new attendance record
│   ├── attendance-edit.php    # Edit existing record
│   └── attendance-delete.php  # Delete handler
├── login.php                  # Login page (standalone)
├── logout.php                 # Session destroy + redirect
├── setup.php                  # First-run admin account creator
└── index.php                  # Front controller / router
```

## Features

- **Authentication** — Login/logout with session-based security
- **Dashboard** — Overview with total records, gender breakdown, and today's attendance
- **Attendance CRUD** — Create, read, update, and delete attendance records
- **Search & Sort** — Filter by employee name, sort by name/date/check-in time
- **Pagination** — 10 records per page with page navigation
- **Responsive** — Desktop sidebar layout + mobile bottom navigation
- **Gender-based UI** — Gender badge colors and avatar initials
- **Real-time Stats** — Average check-in time, late count, and summary boxes

## Assignment Overview

This project was built as a solution to the following assignment:

> Build a simple admin page for employee attendance using the **Backstrap** template.
>
> **a.** List page of employees who have clocked in, with update, delete, sort by & pagination
>
> **b.** Input form with fields: Name, Address, Gender, Attendance date, Check-in time, Check-out time

### Status

| Requirement                        | Status | Implementation                              |
|------------------------------------|--------|---------------------------------------------|
| a. List page                       | ✅     | `pages/attendance-list.php`                 |
| a. Update (edit)                   | ✅     | Edit button → `pages/attendance-edit.php`   |
| a. Delete                          | ✅     | Delete button + confirm → `attendance-delete.php` |
| a. Sort by                         | ✅     | Dropdown + clickable column headers         |
| a. Pagination                      | ✅     | 10/page, numbered pages, prev/next          |
| b. Name field                      | ✅     | Text input with icon                        |
| b. Address field                   | ✅     | Textarea                                    |
| b. Gender field                    | ✅     | Select dropdown (Male / Female)             |
| b. Attendance date field           | ✅     | Date picker                                 |
| b. Check-in time field             | ✅     | Time picker                                 |
| b. Check-out time field            | ✅     | Time picker                                 |

All assignment requirements are **fully completed**.

## How to Run

1. Import `database/attendance.sql` into your MySQL server 
2. Adjust `config/database.php` if your MySQL credentials differ
3. Access `http://localhost/Test%20FrontEnd/setup.php` to create the admin account
4. Login with username **`admin`** and password **`admin123`**
5. Start managing attendance records

## Complete Documentation

[View on Canva](https://canva.link/fivt3ybg21fze5y)

