# Nutri+ — Projeto Completo (Vue + PHP + MySQL)

Front-end em **Vue 3 (Vite)** consumindo um back-end **PHP puro em formato de API JSON**, com **MySQL** como banco de dados.

## Estrutura

```
nutri-plus-mysql/
├── backend/
│   ├── config/         → conexão MySQL (database.php) e CORS/JSON (cors.php)
│   ├── models/          → Usuario, Profissional, Dieta, Treino, Evolucao
│   ├── api/             → endpoints JSON (auth, dieta, treino, evolucao, profissionais)
│   └── database/schema.sql
└── frontend/
    ├── src/
    │   ├── views/        → LoginView, DashboardView, DietaView, TreinoView, EvolucaoView, ProfissionaisView
    │   ├── router/        → rotas + proteção de acesso
    │   ├── services/api.js → chamadas fetch para o back-end
    │   └── assets/style.css → tema visual do Nutri+
    ├── package.json
    └── vite.config.js
```

## Como rodar o back-end (PHP + MySQL)

1. Crie o banco e rode o script (via linha de comando, phpMyAdmin ou MySQL Workbench):
   ```bash
   mysql -u root -p -e "CREATE DATABASE nutriplus"
   mysql -u root -p nutriplus < backend/database/schema.sql
   ```
2. Edite `backend/config/database.php` com usuário/senha do seu MySQL.
3. Suba o servidor PHP embutido a partir da pasta `backend`:
   ```bash
   cd backend
   php -S localhost:8000
   ```
   > Requer a extensão `pdo_mysql` habilitada no PHP (vem ativada por padrão no XAMPP/WAMP/Laragon).

## Como rodar o front-end (Vue)

1. Instale as dependências:
   ```bash
   cd frontend
   npm install
   ```
2. Rode o servidor de desenvolvimento:
   ```bash
   npm run dev
   ```
3. Acesse `http://localhost:5173`.

> O front-end espera o back-end rodando em `http://localhost:8000`. Se mudar a porta do PHP, ajuste `BASE_URL` em `frontend/src/services/api.js` e a origem liberada em `backend/config/cors.php`.

## Fluxo de autenticação

- O PHP usa sessão nativa (`$_SESSION`) e cookie de sessão.
- O front-end sempre chama `fetch` com `credentials: 'include'` para enviar esse cookie.
- As rotas protegidas no Vue (`meta: { requiresAuth: true }`) checam `/api/auth/eu.php` antes de liberar acesso.

## Observações

- Os blocos de "Calorias", "Passos" e "Frequência cardíaca" no Dashboard ainda usam dados de exemplo — não há endpoint para eles ainda.
- Próximos passos do Kanban: chat com profissionais, streaks/gamificação, integração Apple Watch, marketplace e plano premium.
