# T20Plus

App para gerenciamento de campanhas, fichas e ferramentas para Tormenta 20 (RPG de mesa).

## 🚀 Tecnologias Utilizadas

- **Backend:** Laravel 13 + PHP 8.3
- **Frontend:** Angular 21 + TypeScript
- **Renderização de grid:** Phaser (grid isométrico)
- **Banco:** MySQL
- **Servidor:** WAMP ou similar

## 📋 Pré-requisitos

- **WAMP** (com PHP 8.3+ e MySQL)
- **Composer**
- **Node.js** 22+ e **npm**
- **Git**

## 🛠️ Instalação e Configuração

### Backend (t20plus-api)

```bash
cd t20plus-api
composer install
copy .env.example .env
php artisan key:generate
php ../scripts/create_db.php
php artisan migrate
php artisan serve
```

### Frontend (t20plus-frontend)

```bash
cd t20plus-frontend
npm install
npm start
```
