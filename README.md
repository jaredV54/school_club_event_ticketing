# School Club Event Ticketing System

A Laravel-based web application for managing school clubs, events, user registrations, and attendance tracking. This system allows students and officers to create and manage clubs, organize events, handle registrations, and track attendance.

## Features

- **User Management**: Role-based access for students, officers, and administrators
- **Club Management**: Create and manage school clubs with officer assignments
- **Event Management**: Organize events with registration capabilities
- **Registration System**: Students can register for events
- **Attendance Tracking**: Log and manage attendance for events
- **Dashboard**: Overview of system statistics and activities
- **Responsive UI**: Built with TailwindCSS and Blade templates

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP 8.2 or higher**
- **Composer** (PHP dependency manager)
- **Node.js 16+ and npm** (for frontend assets)
- **SQLite** (default database, or configure MySQL/PostgreSQL if preferred)

## Installation

1. **Clone the repository:**
   ```bash
   git clone <your-github-repo-url>
   cd school_club_event_ticketing
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Environment Configuration:**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and configure the database settings for MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=school_club_event_ticketing
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Database Migrations:**
   ```bash
   php artisan migrate
   ```

6. **Seed the Database (Optional - adds sample data):**
   ```bash
   php artisan db:seed
   ```

## Running the Application

### Development Mode
To run the application in development mode with hot reloading:
```bash
composer run dev
```
This will start the Laravel server, queue worker, logs, and Vite dev server concurrently.

### Production Mode
For production, serve the application:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Quick Setup (Using Composer Script)

Alternatively, you can use the built-in setup script:
```bash
composer run setup
```
This will install dependencies, copy environment file, generate key, run migrations, install npm packages, and build assets.

## Database Configuration

The application is configured to use MySQL. Make sure you have MySQL installed and running, and update the database credentials in `.env` as shown in the installation steps.

To use SQLite instead:
1. Change `DB_CONNECTION=sqlite` in `.env`
2. Run migrations (SQLite file will be created automatically)

## User Roles

- **Student**: Can view events, register for events, view their attendance
- **Officer**: Can manage their assigned club, create events, manage registrations, track attendance
- **Admin**: Full system access (if implemented)

## Technologies Used

- **Laravel 12**: PHP framework
- **TailwindCSS 4**: Utility-first CSS framework
- **Vite**: Frontend build tool
- **Blade Templates**: Server-side templating
- **SQLite/MySQL/PostgreSQL**: Database options
- **Axios**: HTTP client for AJAX requests

## Project Structure

- `app/Http/Controllers/`: Application controllers
- `app/Models/`: Eloquent models
- `resources/views/`: Blade templates
- `routes/`: Route definitions
- `database/migrations/`: Database schema
- `database/seeders/`: Database seeders for sample data

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `composer run test`
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
