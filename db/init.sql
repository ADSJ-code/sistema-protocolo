CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user'
);

INSERT INTO usuarios (nome, email, senha, role) VALUES 
('Admin Teste', 'admin@teste.com', '$2y$10$K88aSxatRyN61MAxtc9zE.1u3zCKwvyzElcP3sFLU0FzPAw2mVf6C', 'admin');