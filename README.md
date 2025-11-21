# Protocol System

## Objective
Demonstrate backend proficiency in **Role-Based Access Control (RBAC)** and secure session management using pure PHP and MySQL.

## Stack
* Pure PHP
* MySQL
* Docker / Docker Compose

---

## ⚡ Quick Start

### Prerequisites
* Docker and Docker Compose installed.

### Run Application

Execute this command in the root directory:

```bash
docker-compose up --build -d
```

The application will be available at: http://localhost

### 🔑 Demo Access
Use these credentials for immediate access to the administrative dashboard:

User: admin@teste.com

Password: 123456 (This is the plain-text password for the test user defined in the db/init.sql file. The system uses PHP's password_hash() for secure storage.)

Troubleshooting
If the database initialization fails, ensure your SQL dump file is correctly named ./db/init.sql.