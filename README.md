# To-Do Task Application

This is a task management application built with **Laravel 12 (PHP)** for the backend and **Vue.js 3** for the frontend, using **MySQL** as the database and Docker for containerization.

## Features
- Create new tasks
- View the latest 5 incomplete tasks
- Mark tasks as completed

## Prerequisites
Ensure you have the following installed:
- Docker

## Setup and Installation
### 1. Clone the Repository
```sh
git clone <repository-url>
cd <repository-folder>
```

### 2. Start the Application Using Docker
```sh
docker-compose up -d --build
```

### 3. Run Laravel Migrations
```sh
docker exec -it laravel_app php artisan migrate
```

### 4. Access the Application
- **Backend API**: `http://localhost:8000/api`
- **Frontend UI**: `http://localhost:8080`

### 5. Running Tests
To run backend tests:
```sh
docker exec -it backend-api php artisan test
```

To run frontend tests:
```sh
docker exec -it frontend-ui npm test
```

## API Endpoints
- `POST /api/tasks` - Create a new task
- `GET /api/tasks` - Get latest 5 incomplete tasks
- `PATCH /api/tasks/{id}/complete` - Mark task as completed
