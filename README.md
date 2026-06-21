# 📚 LetterIn

## Design and Implementation of a Book Review Information System Based on Rating and Music Curation on the LetterIn Platform

LetterIn is a web-based social book review platform developed using the Laravel Framework. The application allows users to discover books through the Google Books API, organize personal bookshelves, share reviews and ratings, recommend songs that match a book's atmosphere, and interact with other readers through likes, comments, follows, and notifications. An administrator dashboard is also provided to moderate reports and manage community safety.

---

# ✨ Features

## 👤 Guest

- View homepage
- Browse books
- Search books
- View book details
- Read public reviews
- Register
- Login

---

## 👥 Registered User

- Browse books from Google Books API
- Search books
- View book details
- Create custom bookshelves
- Add books to favorites
- Track reading status
- Write book reviews
- Give ratings
- Recommend songs in reviews
- Edit or delete personal reviews
- Like reviews
- Comment on reviews
- Report inappropriate reviews
- Follow other users
- Receive notifications
- Edit profile
- Logout

---

## 🛠️ Administrator

- View dashboard
- Manage users
- Moderate reviews
- Review user reports
- Ban users
- Remove inappropriate content

---

# 🗺️ Sitemap

```text
Guest
├── Home
├── Browse Books
├── Search Books
├── Book Details
├── Login
└── Register

Registered User
├── Home
├── Browse Books
├── Search Books
├── Book Details
├── Favorites
├── Bookshelves
├── Reading Status
├── Reviews
├── Notifications
├── Profile
└── Logout

Administrator
├── Dashboard
├── User Management
├── Review Moderation
├── Reports
└── Banned Users
```

---

# 🧰 Tech Stack

## Frontend

- HTML5
- CSS3
- JavaScript
- Blade Template Engine

## Backend

- PHP
- Laravel Framework

## Database

- MySQL
- Laravel Eloquent ORM

## External API

- Google Books API
- Deezer API

## Development Tools

- Composer
- Git & GitHub
- Laragon / XAMPP
- Visual Studio Code

---

# ⚙️ Database Configuration

Configure the database credentials in the `.env` file.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_letterin
DB_USERNAME=root
DB_PASSWORD=
```

---

# 🔄 System Flow

```text
                  ┌───────────────────┐
                  │       Start       │
                  └─────────┬─────────┘
                            │
                            ▼
                  Register / Login
                            │
                            ▼
                Browse & Search Books
                 (Google Books API)
                            │
                            ▼
                    Select a Book
                            │
        ┌───────────────────┼────────────────────┐
        │                   │                    │
        ▼                   ▼                    ▼
   View Details       Add to Favorites    Add to Bookshelf
        │
        ▼
   Track Reading Status
        │
        ▼
     Write Review
(Rating + Review + Songs)
        │
        ▼
   Publish Review
        │
        ▼
Other Users Can Interact
(Comment • Like • Follow • Report)
        │
        ▼
 Receive Notifications
        │
        ▼
   Administrator Reviews Reports
        │
        ▼
         End
```

## Workflow Overview

1. Users register or log into their LetterIn account.
2. Users browse or search books using the Google Books API.
3. Users can add books to favorites or organize them into custom bookshelves.
4. Users can update their reading status.
5. Users write reviews by providing:
   - Rating
   - Review content
   - Recommended songs
6. Published reviews become visible to the community.
7. Other users can:
   - Like reviews
   - Comment on reviews
   - Follow reviewers
   - Report inappropriate reviews
8. Notifications are generated for social interactions.
9. Administrators moderate reports, remove inappropriate content, and ban users when necessary.

---

# 🗄️ Database Management System

LetterIn uses **MySQL** as its relational database management system with **Laravel Eloquent ORM**. Book information is retrieved dynamically from the Google Books API, while user-generated content and social interactions are stored locally in the database.

| Table | Purpose |
|-------|---------|
| `users` | Stores user accounts, authentication, and profile information. |
| `books` | Stores books retrieved from the Google Books API. |
| `reviews` | Stores user ratings, reviews, reading status, and recommended songs. |
| `bookshelves` | Stores user-created bookshelves. |
| `bookshelf_book` | Associates books with bookshelves. |
| `favorite_books` | Stores users' favorite books. |
| `user_book_statuses` | Tracks reading status and reading progress. |
| `follows` | Stores follower and following relationships. |
| `review_comments` | Stores comments on reviews. |
| `review_likes` | Stores likes on reviews. |
| `review_reports` | Stores reports for inappropriate reviews. |
| `reports` | Stores reports handled by administrators. |
| `notifications` | Stores user notifications. |
| `banned_users` | Stores banned user information. |

### Main Relationships

- One user can write many reviews.
- One book can have many reviews.
- One user can own multiple bookshelves.
- One bookshelf can contain multiple books.
- One book can belong to multiple bookshelves.
- Users can follow other users.
- Users can like and comment on reviews.
- Users can report inappropriate reviews.
- Administrators manage reports and banned users.

---

# 👨‍💻 Team Members

| Name | Role | Responsibilities |
|------|------|------------------|
| Samara Wardasadiya | Project Manager / Fullstack Developer | Managed project planning, implemented backend features, designed the database, integrated Google Books API, and coordinated development activities. |
| Kadiva Alifia Nurhidayah | Fullstack Developer | Designed UI/UX, developed frontend interfaces, implemented responsive layouts, and integrated frontend components with backend services. |
| Jasmine Aulia Santoso | Fullstack Developer | Designed system architecture, created ERD and UML diagrams, implemented database relationships, developed backend modules, and performed system testing and integration. |

---
