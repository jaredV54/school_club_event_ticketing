# School Club Event Ticketing System - Overview

The School Club Event Ticketing System is a Laravel-based web application that manages school clubs, events, user registrations, and attendance tracking with role-based access control. Here's the overall flow of the system:

## User Roles and Access
- **Students**: Can register for events, view their registrations and attendance
- **Officers**: Manage their assigned club's events, approve registrations, track attendance
- **Admins**: Full system access including user/club management and approvals

## System Flow

### 1. User Onboarding
- **Registration**: Users register with name, email, club selection, and password → creates `PendingUserAccount`
- **Approval**: Admin reviews and approves pending accounts → creates `User` with 'student' role
- **Login**: Approved users can log in; pending accounts show approval message

### 2. Club and Event Setup
- **Club Creation**: Admin creates school clubs
- **Event Creation**: Officers create events for their assigned clubs with details like title, description, date/time, capacity, and location

### 3. Event Registration Process
- **Student Registration**: Students browse active events and submit registration requests → creates `PendingEventRegistration`
- **Approval Workflow**: Officers (for their club's events) or admins review and approve/reject registrations
- **Confirmed Registration**: Approved registrations create `EventRegistration` records with unique ticket codes and 'registered' status

### 4. Attendance Management
- **Attendance Logging**: Officers scan/log attendance using ticket codes → creates `AttendanceLog` and updates registration status to 'attended'
- **Automated Absent Marking**: Scheduled command runs to mark past events as 'passed' and update un-attended registrations to 'absent' status

### 5. Data Management and Views
- **Dashboard**: Role-based overview showing relevant statistics (events, registrations, attendance)
- **CRUD Operations**: Users can view, create, edit, delete records based on permissions
- **Filtering/Search**: Advanced filtering on registrations and attendance by event, user, dates, etc.
- **API Endpoints**: AJAX-powered search and suggestions for user/event selection

## Key Features
- **Approval System**: Two-tier approvals for user accounts and event registrations
- **Capacity Management**: Events have capacity limits enforced during registration approval
- **Duplicate Prevention**: System prevents duplicate registrations and attendance logs
- **Role-Based Security**: Middleware ensures users only access authorized functions
- **Responsive UI**: Built with TailwindCSS and Blade templates

## Database Models
- `User`: Authenticated users with roles (student, officer, admin)
- `Club`: School clubs managed by officers
- `Event`: Club events with capacity and status tracking
- `EventRegistration`: Confirmed registrations with ticket codes
- `PendingEventRegistration`: Registration requests awaiting approval
- `PendingUserAccount`: User accounts awaiting admin approval
- `AttendanceLog`: Attendance records linked to registrations

## Technologies Used
- **Laravel 12**: PHP framework with MVC architecture
- **TailwindCSS 4**: Utility-first CSS framework
- **Vite**: Frontend build tool with hot reloading
- **Blade Templates**: Server-side templating engine
- **SQLite/MySQL/PostgreSQL**: Database options
- **Axios**: HTTP client for AJAX requests

The system ensures controlled access, prevents over-registration, and provides comprehensive tracking from initial user signup through event attendance.