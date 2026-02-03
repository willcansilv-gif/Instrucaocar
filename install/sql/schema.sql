SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil ENUM('admin','mecanica','usuario','financiador') NOT NULL DEFAULT 'usuario',
    status ENUM('ativo','inativo','pendente') NOT NULL DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mecanicas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    cnpj VARCHAR(20) NOT NULL UNIQUE,
    razao_social VARCHAR(160) NOT NULL,
    status ENUM('pendente','aprovada','reprovada') NOT NULL DEFAULT 'pendente',
    reputacao_tecnica DECIMAL(5,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mecanicas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS veiculos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    iuv CHAR(17) NOT NULL UNIQUE,
    placa VARCHAR(10) NOT NULL,
    marca VARCHAR(80) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    ano SMALLINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS historico_proprietarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE DEFAULT NULL,
    motivo_transferencia VARCHAR(120) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_hist_veiculo FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    CONSTRAINT fk_hist_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registros_manutencao (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT UNSIGNED NOT NULL,
    mecanica_id INT UNSIGNED NOT NULL,
    descricao TEXT NOT NULL,
    km INT UNSIGNED NOT NULL,
    data_manutencao DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_manut_veiculo FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    CONSTRAINT fk_manut_mecanica FOREIGN KEY (mecanica_id) REFERENCES mecanicas(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pecas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    fabricante VARCHAR(120) DEFAULT NULL,
    codigo VARCHAR(80) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS manutencao_pecas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manutencao_id INT UNSIGNED NOT NULL,
    peca_id INT UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 1,
    valor_unitario DECIMAL(10,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_manut_peca_manut FOREIGN KEY (manutencao_id) REFERENCES registros_manutencao(id),
    CONSTRAINT fk_manut_peca_peca FOREIGN KEY (peca_id) REFERENCES pecas(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orcamentos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manutencao_id INT UNSIGNED NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    status ENUM('aberto','aprovado','rejeitado') NOT NULL DEFAULT 'aberto',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orc_manut FOREIGN KEY (manutencao_id) REFERENCES registros_manutencao(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS uploads_fotos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manutencao_id INT UNSIGNED NOT NULL,
    tipo ENUM('antes','durante','depois') NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_upload_manut FOREIGN KEY (manutencao_id) REFERENCES registros_manutencao(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS score_veicular (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT UNSIGNED NOT NULL,
    score INT UNSIGNED NOT NULL,
    classificacao ENUM('baixo','medio','alto','excelente') NOT NULL,
    calculado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_score_veiculo FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS auditoria (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED DEFAULT NULL,
    acao VARCHAR(120) NOT NULL,
    entidade VARCHAR(120) NOT NULL,
    entidade_id INT UNSIGNED DEFAULT NULL,
    ip VARCHAR(45) DEFAULT NULL,
    detalhes JSON DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auditoria_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS logs_acesso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED DEFAULT NULL,
    ip VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    sucesso TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS autorizacoes_visualizacao (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    permitido_ate DATE NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_aut_veiculo FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    CONSTRAINT fk_aut_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE INDEX idx_hist_veiculo ON historico_proprietarios(veiculo_id, data_inicio);
CREATE INDEX idx_manut_veiculo ON registros_manutencao(veiculo_id, data_manutencao);
CREATE INDEX idx_score_veiculo ON score_veicular(veiculo_id, calculado_em);

DELIMITER $$
CREATE TRIGGER trg_registros_manutencao_immutable
BEFORE UPDATE ON registros_manutencao
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Registros de manutenção são imutáveis.';
END$$
DELIMITER ;

SET FOREIGN_KEY_CHECKS = 1;
