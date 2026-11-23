# 🐾 PetControl

Sistema simples de gestão de clientes e pets para pet shops.
Trabalho final de Programação II — Unimontes.

## Tecnologias
- PHP 8 orientado a objetos (PDO)
- MySQL 8
- Bootstrap 5 + jQuery (AJAX/JSON)
- Integração externa: API ViaCEP (preenchimento automático de endereço)

## Funcionalidades
- Login / logout (senha com `password_hash`)
- CRUD de clientes (com busca)
- CRUD de pets vinculados a clientes (com busca)
- Autocompletar endereço pelo CEP

## Como rodar
```bash
docker compose up -d --build
```
Acesse http://localhost:8080

Usuário padrão: `admin@petcontrol.com` / `admin123`

Para recriar o banco do zero: `docker compose down -v && docker compose up -d --build`

## Estrutura
```
db/schema.sql           banco de dados (3 tabelas)
src/Database.php        conexão PDO
src/Auth.php            login/logout/sessão
src/Repositorio.php     CRUD genérico (classe base)
src/Cliente.php, Pet.php
src/CrudController.php  responde JSON
public/                 páginas e API (api/*.php)
public/assets/app.js    jQuery/AJAX
```
