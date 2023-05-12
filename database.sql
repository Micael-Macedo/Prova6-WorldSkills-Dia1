create database myProdutos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use myProdutos;

create table usuarios(
	id int primary key auto_increment,
    email varchar(255),
    senha varchar(255),
    nivelAcesso int
);
create table categorias(
	id int primary key auto_increment,
    nome varchar(255)
);
create table fornecedor(
	id int primary key auto_increment,
    nome varchar(255)
);
create table produtos(
	id int primary key auto_increment,
    nome varchar(255),
    valor varchar (255),
    categoriaId int,
    fornecedorId int,
    localEstoque varchar(255),
    qtdDisponivel int,
    FOREIGN KEY (categoriaId) REFERENCES categorias(id),
	FOREIGN KEY (fornecedorId) REFERENCES fornecedor(id)
);
create table avaliacao(
	id int primary key auto_increment,
    usuarioId int,
    comentario varchar(255),
    classificacao varchar(255),
    produtoId int,
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id),
	FOREIGN KEY (produtoId) REFERENCES produtos(id)
);
create table imagens(
    id int primary key auto_increment,
    produtoId int,
    base64Imagem varchar(500),
    arquivo varchar(255),
    FOREIGN KEY (produtoId) REFERENCES produtos(id),
);

