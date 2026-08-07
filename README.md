# IT Ticketing System

A web-based IT Ticketing System built with **Laravel 12** and **Bootstrap 5** to streamline issue reporting, assignment, and tracking within a software development team.

---

## Overview

This application simulates an internal ticket management system used by software development teams to improve collaboration between Quality Assurance (QA), Developers, and Administrators.

The system allows QA to report bugs, Administrators to manage and assign tickets, and Developers to resolve issues while maintaining a complete activity history.

---

## Features

### Authentication

- Secure Login
- Role-based Access Control
- Profile Management

### Dashboard

- Ticket Statistics
- Recent Tickets
- Recent Activities

### Ticket Management

- Create Ticket
- Edit Ticket
- Delete Ticket
- Ticket Detail
- Ticket Number Generator

### Assignment

- Assign Ticket to Developer
- Update Priority
- Update Status

### Collaboration

- Comment System
- Activity Log
- Screenshot Upload

### Search & Filter

- Search by Ticket Number
- Search by Title
- Search by Description
- Filter by Status
- Filter by Priority
- Filter by Assigned Developer

### Others

- Pagination
- Responsive Layout
- Form Validation
- Flash Message
- Soft Delete

---

## Tech Stack

- Laravel 12
- PHP 8.3
- Bootstrap 5
- MySQL
- Blade Template Engine
- Eloquent ORM

---

## User Roles

### Administrator

Responsible for managing the overall ticket workflow.

Permissions:

- View all tickets
- Create/Edit/Delete tickets
- Assign developers
- Manage ticket priority
- Manage ticket status
- View activity logs

---

### Developer

Responsible for resolving assigned tickets.

Permissions:

- View assigned tickets
- Update ticket status
- Add comments
- Update personal profile

---

### Quality Assurance (QA)

Responsible for reporting and verifying issues.

Permissions:

- Create new ticket
- Upload screenshots
- Edit reported ticket
- Verify bug fixes
- Close or Reopen ticket
- Add comments

---

## Demo Account

### Administrator

Email

```
admin@novatech.local
```

Password

```
password
```

---

### Developer

Email

```
developer@novatech.local
```

Password

```
password
```

---

### Quality Assurance

Email

```
qa@novatech.local
```

Password

```
password
```

---

## Project Structure

```
app/
├── Http/
├── Models/
├── Services/
├── Enums/
├── Observers/
├── Providers/

database/
├── migrations/
├── seeders/
├── factories/

resources/
├── views/
├── css/
├── js/

routes/
```

---

## Installation

```bash
git clone https://github.com/username/it-ticketing-system.git

cd it-ticketing-system

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan storage:link

php artisan serve
```

---

## Future Improvements

- Email Notification
- Export PDF
- Ticket Attachment History
- Advanced Reporting Dashboard
- REST API Integration

---

## Author

Audrey Gracia Chandra

Universitas Multimedia Nusantara

Frontend Developer
