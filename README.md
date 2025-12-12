Rentify - House Rental System

Rentify is a comprehensive web application built with Laravel for managing house rentals. Users can browse, search, and book properties, while admins manage properties, bookings, and users through an intuitive dashboard.

---

Table of Contents

1. Project Overview
2. Setup Instructions
3. Usage Guide
4. Contributing
5. License
6. Screenshots

---

Project Overview

* Project Name: Rentify
* Type: Web Application (House Rental System)
* Framework: Laravel PHP
* Database: MySQL
* Key Features:

  * User registration, login, and profile management
  * Property listing with images
  * Property search and filters (location, price, availability)
  * Booking system for users
  * Admin dashboard for managing properties, bookings, and users
  * Image upload for properties
  * Optional email notifications for bookings

---

Setup Instructions

Step 1: Clone the repository

git clone [https://github.com/Malik-Mushtaq/Laravel-Project.git](https://github.com/Malik-Mushtaq/Laravel-Project.git)
cd Laravel-Project

Step 2: Switch to the branch

git checkout final-updated-project

Step 3: Install PHP dependencies

composer install

Step 4: Set up environment file

cp .env.example .env   # Linux/macOS
copy .env.example .env # Windows

Edit `.env` and set your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rentify
DB_USERNAME=root
DB_PASSWORD=your_password

Step 5: Generate application key

php artisan key:generate

Step 6: Run migrations and seeders

php artisan migrate --seed

Step 7: Install frontend dependencies

npm install
npm run dev

Step 8: Serve the application

php artisan serve

Visit [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

Step 9: Enable image uploads

chmod -R 775 public/assets/properties

Step 10: Configure email (optional)

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

---

Usage Guide

User Features:

1. Browse available properties with images and details.
2. Search and filter by location, price, or availability.
3. Book properties using the booking form.
4. Manage user profile information.

Admin Features:

1. Add, edit, or delete properties.
2. View and manage all bookings.
3. Manage users and roles.
4. Upload property images via the admin dashboard.

---

License

This project is open-source under the MIT License.

---
