-- ---------------------------------------------------------------------------
-- BANCO DE DADOS DO MINI MARKETPLACE (MariaDB)
--
-- Como rodar (no terminal, dentro da pasta do projeto):
--     sudo mariadb < banco.sql
--
-- Pode rodar quantas vezes quiser: ele apaga as tabelas e cria tudo de novo.
-- ---------------------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS mini_marketplace CHARACTER SET utf8mb4;

-- Usuário que o PHP vai usar para entrar no banco (o mesmo de classes/Banco.php)
CREATE USER IF NOT EXISTS 'aula'@'localhost' IDENTIFIED BY 'aula123';
GRANT ALL PRIVILEGES ON mini_marketplace.* TO 'aula'@'localhost';

USE mini_marketplace;

-- produtos tem que ser apagada primeiro, porque ela depende de vendedores
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS vendedores;

-- Cada TABELA vira uma CLASSE no PHP, e cada LINHA vira um OBJETO.
-- Cada COLUNA vira uma PROPRIEDADE. Repare que os tipos se parecem:
--     INT -> int     VARCHAR -> string     DECIMAL -> float

CREATE TABLE vendedores (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nome  VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    nota  DECIMAL(2,1) NOT NULL DEFAULT 5.0
);

CREATE TABLE produtos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    descricao   VARCHAR(255) NULL,              -- NULL = pode ficar vazio (?string no PHP)
    preco       DECIMAL(10,2) NOT NULL,
    desconto    INT NOT NULL DEFAULT 0,         -- em porcentagem
    estoque     INT NOT NULL DEFAULT 0,
    tipo        VARCHAR(10) NOT NULL,           -- 'fisico' ou 'digital'
    tamanho_mb  INT NULL,                       -- só os digitais usam
    vendedor_id INT NOT NULL,

    -- CHAVE ESTRANGEIRA: todo produto tem que apontar para um vendedor que existe
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id)
);

INSERT INTO vendedores (id, nome, email, nota) VALUES
    (1, 'João Games', 'joao@email.com',  4.8),
    (2, 'Maria Tech', 'maria@email.com', 4.2),
    (3, 'Ana Cursos', 'ana@email.com',   5.0);

INSERT INTO produtos (id, nome, descricao, preco, desconto, estoque, tipo, tamanho_mb, vendedor_id) VALUES
    (1, 'Teclado Mecânico', 'Switch azul, com luz RGB.',          250.00, 10,  5, 'fisico',  NULL, 1),
    (2, 'Mouse Gamer',      NULL,                                 120.00,  0, 10, 'fisico',  NULL, 1),
    (3, 'Headset USB',      NULL,                                 180.00,  0,  0, 'fisico',  NULL, 2),
    (4, 'Webcam HD',        NULL,                                  90.00,  0,  3, 'fisico',  NULL, 2),
    (5, 'Curso de PHP',     'Do zero até orientação a objetos.',   60.00, 50,  0, 'digital', 2048, 3),
    (6, 'E-book de POO',    NULL,                                  25.00,  0,  0, 'digital',   12, 3);
