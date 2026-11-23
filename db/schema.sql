-- Banco de dados do PetControl
CREATE DATABASE IF NOT EXISTS petcontrol CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE petcontrol;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(120),
    cep VARCHAR(9),
    logradouro VARCHAR(150),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    uf CHAR(2),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    nome VARCHAR(80) NOT NULL,
    especie VARCHAR(40) NOT NULL,
    raca VARCHAR(80),
    nascimento DATE NULL,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pet_cliente FOREIGN KEY (cliente_id)
        REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Usuário padrão: admin@petcontrol.com / admin123
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@petcontrol.com', '$2y$10$MswOl5tZ9YiEDs5ovEr5au6deB6gxX64i9B0H.QcqCaX2oiStojWa')
ON DUPLICATE KEY UPDATE nome = nome;
