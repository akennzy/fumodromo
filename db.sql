CREATE DATABASE IF NOT EXISTS fumodromo;
USE fumodromo;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    razao_mudanca TEXT NOT NULL,
    medos_preocupacoes TEXT NOT NULL,
    data_inicio_fumo DATE NOT NULL,
    tentativas_parar INT NOT NULL,
    cigarros_dia INT NOT NULL,
    cigarros_maco INT NOT NULL,
    valor_maco DECIMAL(10,2) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_parada DATETIME DEFAULT NULL
);

CREATE TABLE diario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    humor INT NOT NULL, -- 1: Muito Mal, 2: Mal, 3: Neutro, 4: Bem, 5: Muito Bem
    nivel_ansiedade INT NOT NULL, -- 1: Muito Baixo a 5: Muito Alto
    nivel_vontade INT NOT NULL, -- 1: Muito Baixo a 5: Muito Alto
    texto TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela para o checklist diário
CREATE TABLE IF NOT EXISTS checklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    item VARCHAR(255) NOT NULL,
    completado BOOLEAN DEFAULT FALSE,
    data DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    UNIQUE KEY unique_item_por_dia (usuario_id, item, data)
);

-- Tabela para entradas rápidas do diário
CREATE TABLE IF NOT EXISTS diario_rapido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    sentimento ENUM('otimo', 'bom', 'regular', 'ruim', 'pessimo') NOT NULL,
    nota TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Adicionar campos necessários na tabela usuarios se não existirem
ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS data_parada DATETIME,
ADD COLUMN IF NOT EXISTS cigarros_por_dia INT DEFAULT 20,
ADD COLUMN IF NOT EXISTS preco_maco DECIMAL(10,2) DEFAULT 10.00,
ADD COLUMN IF NOT EXISTS nivel INT DEFAULT 1,
ADD COLUMN IF NOT EXISTS pontos INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS proxima_pontuacao INT DEFAULT 100,
ADD COLUMN IF NOT EXISTS notif_diario BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS notif_conquistas BOOLEAN DEFAULT TRUE,
ADD COLUMN IF NOT EXISTS notif_milestones BOOLEAN DEFAULT TRUE;

-- Tabela de conquistas disponíveis
CREATE TABLE IF NOT EXISTS conquistas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    pontos INT NOT NULL DEFAULT 10,
    icone VARCHAR(50) NOT NULL,
    tipo ENUM('diario', 'semanal', 'mensal', 'especial') NOT NULL
);

-- Tabela para registrar conquistas do usuário
CREATE TABLE IF NOT EXISTS usuario_conquistas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    conquista_id INT NOT NULL,
    data_obtida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (conquista_id) REFERENCES conquistas(id),
    UNIQUE KEY unique_conquista (usuario_id, conquista_id)
);

-- Tabela para histórico de tentativas
CREATE TABLE IF NOT EXISTS historico_tentativas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL,
    cigarros_evitados INT NOT NULL,
    economia DECIMAL(10,2) NOT NULL,
    duracao_dias INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Criar tabela de objetivos pessoais
CREATE TABLE IF NOT EXISTS objetivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    texto VARCHAR(255) NOT NULL,
    concluido BOOLEAN DEFAULT FALSE,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Criar tabela de marcos de saúde
CREATE TABLE IF NOT EXISTS marcos_saude (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dias INT NOT NULL,
    descricao TEXT NOT NULL,
    porcentagem_melhora INT NOT NULL
);

-- Inserir conquistas iniciais
INSERT INTO conquistas (titulo, descricao, pontos, icone, tipo) VALUES
('Primeiro Dia', 'Complete seu primeiro dia sem fumar', 10, 'bi-1-circle', 'especial'),
('Primeira Semana', 'Complete uma semana sem fumar', 50, 'bi-7-circle', 'especial'),
('Primeiro Mês', 'Complete um mês sem fumar', 200, 'bi-calendar-check', 'especial'),
('Diário Dedicado', 'Registre seu humor por 5 dias seguidos', 30, 'bi-journal-check', 'semanal'),
('Hidratação em Dia', 'Complete o checklist de beber água por 3 dias', 20, 'bi-droplet', 'semanal'),
('Meditação Constante', 'Medite por 7 dias seguidos', 40, 'bi-peace', 'semanal'),
('Economia Inicial', 'Economize R$ 50,00 não comprando cigarros', 25, 'bi-piggy-bank', 'especial'),
('Super Economia', 'Economize R$ 200,00 não comprando cigarros', 100, 'bi-cash-stack', 'especial'),
('Exercício Regular', 'Complete o checklist de exercícios por 5 dias', 35, 'bi-heart-pulse', 'semanal');

-- Inserir marcos de saúde padrão
INSERT INTO marcos_saude (dias, descricao, porcentagem_melhora) VALUES
(1, 'Níveis de oxigênio normalizados', 10),
(2, 'Melhora no olfato e paladar', 10),
(3, 'Respiração mais fácil', 10),
(7, 'Melhora na circulação', 15),
(14, 'Melhora na função pulmonar', 15),
(30, 'Redução significativa na tosse', 20),
(90, 'Função pulmonar aumenta até 30%', 20);

-- Adicionar índices para melhor performance
CREATE INDEX idx_usuario_conquistas_usuario ON usuario_conquistas(usuario_id);
CREATE INDEX idx_historico_tentativas_usuario ON historico_tentativas(usuario_id);
CREATE INDEX idx_checklist_usuario_data ON checklist(usuario_id, data);
CREATE INDEX idx_diario_rapido_usuario ON diario_rapido(usuario_id, criado_em);
CREATE INDEX idx_objetivos_usuario ON objetivos(usuario_id);
