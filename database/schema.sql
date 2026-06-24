-- =====================================================
-- BANCO DE DADOS: CARONAS NO CAMPUS
-- Versão: 1.0.0
-- Data: 2026-06-24
-- =====================================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS caronas_campus;
USE caronas_campus;

-- =====================================================
-- TABELA: USUARIOS
-- Armazena informações dos usuários do sistema
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_data_criacao (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: CARONAS
-- Armazena as caronas oferecidas pelos usuários
-- =====================================================
CREATE TABLE IF NOT EXISTS caronas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    origem VARCHAR(150) NOT NULL,
    destino VARCHAR(150) NOT NULL,
    data_saida DATE NOT NULL,
    hora_saida TIME NOT NULL,
    vagas_disponiveis INT NOT NULL DEFAULT 4,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_data_saida (data_saida),
    INDEX idx_origem (origem),
    INDEX idx_destino (destino)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: RESERVAS
-- Armazena as reservas de caronas pelos usuários
-- =====================================================
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carona_id INT NOT NULL,
    usuario_id INT NOT NULL,
    status ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    data_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carona_id) REFERENCES caronas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reserva (carona_id, usuario_id),
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_status (status),
    INDEX idx_data_reserva (data_reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DADOS DE EXEMPLO (Opcional - para testes)
-- =====================================================

-- Inserir usuários de exemplo
INSERT INTO usuarios (nome, email, telefone, senha) VALUES
('João Silva', 'joao@example.com', '(54) 99999-0001', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm'),
('Maria Santos', 'maria@example.com', '(54) 99999-0002', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm'),
('Pedro Oliveira', 'pedro@example.com', '(54) 99999-0003', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/KFm');

-- Inserir caronas de exemplo
INSERT INTO caronas (usuario_id, origem, destino, data_saida, hora_saida, vagas_disponiveis, descricao) VALUES
(1, 'Campus IFRS Ibirubá', 'Centro de Ibirubá', '2026-06-25', '08:00', 3, 'Carona para o centro, saída pontual'),
(2, 'Campus IFRS Ibirubá', 'Estação Rodoviária', '2026-06-25', '17:30', 2, 'Volta para casa, passageiros tranquilos'),
(3, 'Ibirubá', 'Campus IFRS Ibirubá', '2026-06-26', '07:30', 4, 'Ida para o campus, saída cedo');

-- Inserir reservas de exemplo
INSERT INTO reservas (carona_id, usuario_id, status) VALUES
(1, 2, 'confirmada'),
(2, 1, 'pendente');

-- =====================================================
-- FIM DO SCRIPT
-- =====================================================
