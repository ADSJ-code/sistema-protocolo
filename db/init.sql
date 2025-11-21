CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@teste.com', '$2y$10$K88aSxatRyN61MAxtc9zE.1u3zCKwvyzElcP3sFLU0FzPAw2mVf6C', 'admin');

INSERT INTO orders (title, description, status) VALUES 
('Server Maintenance', 'Routine checkup of the main Linux server.', 'Pending'),
('Update Firewall Rules', 'Block incoming traffic from port 8080.', 'In Progress'),
('Employee Onboarding', 'Setup laptop and accounts for new hire (John Doe).', 'Completed');