# Protocol System with Audit Trail

## 🚀 Objective
A robust **Request Management System** built with **Pure PHP** and **MySQL**, demonstrating advanced backend concepts such as **Role-Based Access Control (RBAC)**, **Audit Logging**, and **Version Control** for data integrity.

## 🛠️ Tech Stack
* **Backend:** PHP 7.4 (Native)
* **Database:** MySQL 5.7
* **Infrastructure:** Docker & Docker Compose
* **Frontend:** Pico.css (Semantic HTML)

---

## ✨ Key Features

### 1. Role-Based Access Control (RBAC)
* **Admin:** Can view all requests, approve/deny requests, and view full audit logs.
* **Employee (User):** Can create requests, edit their own pending requests, and track status.

### 2. Audit Trail & Version Control
* Every action (Create, Edit, Approve, Deny) is logged in the database.
* **Edit Tracking:** If a user edits a request, the system records exactly *what* changed (e.g., "Title updated, Description updated").
* **Transparency:** The dashboard clearly shows who created the request and who performed the last action.

### 3. Security
* Passwords are hashed using `password_verify()`.
* SQL Injection protection using Prepared Statements (`mysqli`).
* Session hijacking protection.

---

## ⚡ Quick Start

### Prerequisites
* Docker and Docker Compose installed.

### 1. Clone & Run
Run these commands in the project root:

Build and start the containers

```bash
docker-compose up --build -d
```

The application will be available at: http://localhost

2. database Reset (Important)
If you need to reset the database to the initial state (clearing all created orders), run:

```bash
docker-compose down -v
```

and 

```bash
docker-compose up --build -d
```

### 🔑 Demo Credentials

Use the following accounts to test different access levels:


Admin: admin@test.com
Password: 123456,
Capabilities: Approve/Deny requests, View full history


User: user@test.com
Password: 123456
Capabilities: Create requests, Edit pending requests

### 📂 Project Structure

index.php - Secure Login page.

dashboard.php - Main view customized by user role.

create_order.php - Form for new requests.

edit_order.php - Edit form for pending requests (Version Control).

detalhes_pedido.php - Detailed view with Audit History.

actions.php - Backend controller for processing logic and logging actions.

db/init.sql - Database schema and initial seed data