CREATE DATABASE IF NOT EXISTS `cbc`;

CREATE TABLE IF NOT EXISTS `cbc`.`clube` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do clube (PK)',
  `clube` VARCHAR(255) NOT NULL COMMENT 'Nome completo do clube',
  `saldo_disponivel` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Saldo financeiro disponível em reais',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do registro',
  `data_atualizacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data da última atualização',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cadastro de clubes e seus saldos financeiros';

CREATE TABLE IF NOT EXISTS `cbc`.`recurso` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único do recurso (PK)',
  `recurso` VARCHAR(255) NOT NULL COMMENT 'Descrição do recurso financeiro',
  `saldo_disponivel` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor disponível do recurso',
  `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do registro',
  `data_atualizacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data da última atualização',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Controle de recursos financeiros do sistema';

INSERT INTO `cbc`.`recurso` (`recurso`, `saldo_disponivel`) 
VALUES
('Recurso para passagens', 10000.00),
('Recurso para hospedagens', 10000.00);
