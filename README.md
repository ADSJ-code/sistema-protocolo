# Protocol System

## Objective
Demonstrate backend proficiency in **Role-Based Access Control (RBAC)** and secure session management using pure PHP and MySQL.

## Stack
* Pure PHP
* MySQL (Containerized)
* Apache Web Server (Containerized)

---

## ⚡ Quick Start (For Recruiters/Evaluators)

This project is fully containerized and starts with a single command.

### Prerequisites
* Docker and Docker Compose installed.

### 1. Clone & Run

Clone the repository and start the services:

```bash
git clone https://github.com/ADSJ-code/sistema-protocolo.git
```
```bash
cd sistema-protocolo
```

Build and run the containers

```bash
docker-compose up --build -d
```

2. Access Application
The application will be available at: http://localhost

🔑 Demo Access
Use these credentials for immediate access to the administrative dashboard:

User: admin@teste.com

Password: 123456

(Note: The system uses PHP's password_hash() for secure storage. The plain-text password '123456' is defined for the test user in the ./db/init.sql file.)