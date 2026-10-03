-- ============================================
-- Nutri+ | Script de criação do banco de dados
-- MySQL
-- ============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    especialidade VARCHAR(60) NOT NULL, -- 'Nutricionista' ou 'Personal Trainer'
    registro_profissional VARCHAR(60),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE dietas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome_refeicao VARCHAR(80) NOT NULL,
    alimentos TEXT NOT NULL,
    calorias INT,
    data_refeicao DATE NOT NULL DEFAULT (CURRENT_DATE),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE treinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    profissional_id INT NULL,
    nome_treino VARCHAR(80) NOT NULL,
    tipo VARCHAR(40) NOT NULL,
    duracao_min INT,
    data_treino DATE NOT NULL DEFAULT (CURRENT_DATE),
    observacoes TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE evolucao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_registro DATE NOT NULL DEFAULT (CURRENT_DATE),
    peso DECIMAL(5,2),
    percentual_gordura DECIMAL(4,2),
    observacao TEXT,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_dietas_usuario ON dietas(usuario_id);
CREATE INDEX idx_treinos_usuario ON treinos(usuario_id);
CREATE INDEX idx_evolucao_usuario ON evolucao(usuario_id);
