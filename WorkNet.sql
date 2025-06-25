create database WorkNet;
use WorkNet;
drop database WorkNet;

CREATE TABLE IF NOT EXISTS AcessoUsuario (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(75) NOT NULL,
    email_cliente VARCHAR(75) NOT NULL,
    senha_cliente VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    nome_user VARCHAR(75) NOT NULL,
    email_user VARCHAR(100) NOT NULL,
    senha_user VARCHAR(255) NOT NULL,
    data_nasc DATE NOT NULL,
    sexo_user VARCHAR(10) NOT NULL,
    telefone_user VARCHAR(55) NOT NULL,
    estado_civil VARCHAR(55) NOT NULL,
    cpf_user CHAR(11) NOT NULL,
    nacionalidade_user VARCHAR(55) NOT NULL,
    rg_user VARCHAR(20) NOT NULL,
    endereco_user JSON NOT NULL,
    conhecimentos JSON,
    FOREIGN KEY (id_cliente) REFERENCES AcessoUsuario(id_cliente)
);


select * from usuario;
select * from formacao_idioma;

alter table usuario
add column sobrenome varchar(255) after nome_user,
add column profissao_ou_cargo varchar(255) after sobrenome;

-- Tabela FormacaoIdioma (combina formação acadêmica e idiomas)
CREATE TABLE IF NOT EXISTS formacao_idioma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    escola VARCHAR(100) NOT NULL,
    estado VARCHAR(55) NOT NULL,
    cidade VARCHAR(55) NOT NULL,
    status_fa CHAR(9) NOT NULL,
    data_inicio DATE NOT NULL,
    data_conclusao DATE,
    idioma VARCHAR(55) NOT NULL,
    nivel_idioma VARCHAR(55) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES usuario(id_user)
);

-- Tabela Diversidade (para armazenar informações de diversidade como gênero, orientação sexual, etc.)
CREATE TABLE IF NOT EXISTS diversidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    identidade_de_genero VARCHAR(25) NOT NULL,
    orientacao_sexual VARCHAR(25) NOT NULL,
    cor_ou_raca VARCHAR(25) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES usuario(id_user)
);

ALTER TABLE usuario ADD COLUMN descricao TEXT;

-- -------------------------------------------------------------------------------------------------------------------

