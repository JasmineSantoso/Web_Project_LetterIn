# 📚 LetterIn

## Design and Implementation of a Book Review Information System Based on Rating and Music Curation on the LetterIn Platform

LetterIn is a web-based application designed as a digital book review platform featuring rating systems, user reviews, book recommendations, and music curation to enhance users’ reading experiences. This website aims to help users discover books that match their preferences through community reviews, ratings, and personalized reading atmospheres.

---

# ✨ Features

## 👤 Guest User

* View the homepage
* Browse book collections
* View book details
* Read reviews and ratings from other users
* Search books by title or category
* Register an account
* Login to the system

## 👥 Registered User

* Login and logout
* Edit user profile
* Give book ratings
* Write book reviews
* Save favorite books / wishlist
* Access personalized book recommendations
* Get music curation based on reading mood
* Edit or delete personal reviews

## 🛠️ Admin

* Manage book data
* Manage book categories
* Manage users
* Moderate reviews
* Remove inappropriate content
* View website activity reports

---

# 🗺️ Sitemap / Actor Features

```text
Guest
├── Home
├── Explore Books
├── Book Detail
├── Search Book
├── Login
└── Register

Registered User
├── Dashboard
├── Profile
├── Book Review
├── Rating System
├── Wishlist
├── Music Recommendation
└── Logout

Admin
├── Admin Dashboard
├── Manage Books
├── Manage Categories
├── Manage Users
├── Manage Reviews
└── Reports
```

---

# 🧰 Tech Stack

## Frontend

* HTML5
* CSS3
* JavaScript
* Blade Template Engine

## Backend

* PHP
* Laravel Framework

## Database

* MySQL / SQLite *(adjust according to the project configuration)*

## Additional Tools

* Git & GitHub
* Laragon / XAMPP
* Composer

---

# 🗄️ Database Configuration

## Database Used

The database configuration used in this project:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_letterin
DB_USERNAME=root
DB_PASSWORD=
```

---

# 📑 Table Specifications

## users

| Field      | Type      | Description        |
| ---------- | --------- | ------------------ |
| id         | bigint    | Primary key        |
| name       | varchar   | User name          |
| email      | varchar   | User email         |
| password   | varchar   | User password      |
| role       | enum      | User role          |
| created_at | timestamp | Creation timestamp |

## books

| Field       | Type      | Description        |
| ----------- | --------- | ------------------ |
| id          | bigint    | Primary key        |
| title       | varchar   | Book title         |
| author      | varchar   | Book author        |
| description | text      | Book description   |
| cover       | varchar   | Book cover image   |
| category_id | bigint    | Category relation  |
| created_at  | timestamp | Creation timestamp |

## categories

| Field | Type    | Description   |
| ----- | ------- | ------------- |
| id    | bigint  | Primary key   |
| name  | varchar | Category name |

## reviews

| Field      | Type      | Description        |
| ---------- | --------- | ------------------ |
| id         | bigint    | Primary key        |
| user_id    | bigint    | User relation      |
| book_id    | bigint    | Book relation      |
| rating     | integer   | Book rating        |
| review     | text      | Review content     |
| created_at | timestamp | Creation timestamp |

## wishlists

| Field   | Type   | Description   |
| ------- | ------ | ------------- |
| id      | bigint | Primary key   |
| user_id | bigint | User relation |
| book_id | bigint | Book relation |

---

# 👨‍💻 Team Members

| Name                     | Role                                | Responsibilities                            |
| -------------------------| ----------------------------------- | --------------------------------------------|
| Samara Wardasadiya       | Project Manager / Backend Developer | Develop backend systems and database        |
| Kadiva Alifia Nurhidayah | Frontend Developer                  | Design website UI/UX                        |
| Jasmine Aulia Santoso    | Database Designer                   | Design ERD, UML,  and database relations    |

---

# ⚙️ Installation Guide

## 1. Clone Repository

```bash
git clone https://github.com/JasmineSantoso/Web_Project_LetterIn.git
```

## 2. Enter Project Directory

```bash
cd Web_Project_LetterIn
```

## 3. Install Laravel Dependencies

```bash
composer install
```

## 4. Copy Environment File

```bash
cp .env.example .env
```

## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Configure Database

Edit the `.env` file and adjust the database configuration.

## 7. Run Migration

```bash
php artisan migrate
```

## 8. Run Development Server

```bash
php artisan serve
```

---

# 🚀 Future Development

* Integration with Google Books API
* Reading progress tracker feature
* Social interaction between users
