---
name: run-local
description: Sobe e dirige o portal de notícias (Laravel) localmente no Windows/Laragon — inicia o servidor e abre http://localhost:8000.
---

# Rodar o portal de notícias localmente (Windows + Laragon)

Stack: Laravel 13, PHP 8.3+, MySQL/MariaDB, Vite (Tailwind 4 + TipTap), SSR Blade.
Ambiente local verificado: **Laragon** (PHP + MySQL). PHP e Composer **não estão no PATH** —
use o binário do Laragon pelo caminho completo.

## 0. Descobrir o PHP do Laragon (o caminho tem a versão no nome)

```powershell
$php = (Get-ChildItem "C:\laragon\bin\php" -Filter php.exe -Recurse | Select-Object -First 1).FullName
$php   # ex.: C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe
```

Use `& $php artisan ...` em todos os comandos abaixo. Composer (se precisar) costuma estar em
`C:\laragon\bin\composer\composer.bat`; Node/npm estão no PATH normalmente.

## 1. Garantir o MySQL no ar

O banco é MySQL em `127.0.0.1:3306` (config no `.env`, banco `site_noticias`). Confira:

```powershell
(Test-NetConnection 127.0.0.1 -Port 3306 -WarningAction SilentlyContinue).TcpTestSucceeded
```

Se for `False`, abra o **Laragon → Start All** (sobe o MySQL) e repita.

## 2. Setup inicial — SÓ na primeira vez (ou após clonar limpo)

Pule se `vendor/`, `node_modules/`, `public/build/` e `public/storage` já existem (normalmente já estão).

```powershell
& "C:\laragon\bin\composer\composer.bat" install   # se vendor/ faltar
npm install                                          # se node_modules/ faltar
& $php artisan key:generate                          # se APP_KEY vazio no .env
& $php artisan migrate
& $php artisan db:seed        # cria admin + categorias + 3 posts de exemplo
npm run build                 # gera public/build (assets Vite)
& $php artisan storage:link
```

Idempotência rápida: `& $php artisan migrate:status` deve listar tudo como `Ran`;
`& $php artisan tinker --execute="echo App\Models\Post::count();"` deve ser > 0.

## 3. Servir

```powershell
& $php artisan serve     # http://localhost:8000
```

Se a porta 8000 já responder (`(Test-NetConnection 127.0.0.1 -Port 8000 -WarningAction SilentlyContinue).TcpTestSucceeded`),
provavelmente já há um `serve` rodando — basta abrir o navegador.

Para hot-reload de front (Blade/JS/CSS), em outro terminal: `npm run dev`.

## 4. Dirigir / verificar (não só subir)

Acessos:
- Site: **http://localhost:8000**
- Painel: **http://localhost:8000/admin** → redireciona para `/admin/login`
- Login: `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` do `.env` (padrão `admin@noticias.local` / `admin12345`)

Smoke test das rotas (todas devem dar HTTP 200, exceto `/admin` que faz 302→`/admin/login`):

```powershell
"/","/noticia/nova-geracao-de-chips-promete-mais-eficiencia","/admin/login","/feed.xml","/sitemap.xml","/busca?q=teste" | ForEach-Object {
  try { $r = Invoke-WebRequest ("http://localhost:8000"+$_) -UseBasicParsing -TimeoutSec 8; "{0} -> {1}" -f $_,$r.StatusCode }
  catch { "{0} -> {1}" -f $_, [int]$_.Exception.Response.StatusCode }
}
```

A home deve mostrar a manchete e a grade de notícias; a página de notícia deve conter
JSON-LD `NewsArticle`, Open Graph e a seção de comentários; `/admin/login` deve ter os
campos `email`, `password` e o `_token` (CSRF).

## Notas / pegadinhas

- Login é em **`/admin/login`** — `/login` retorna 404 (esperado).
- `/ads.txt` só responde quando o AdSense está configurado em *Admin → Configurações*; 404 sem isso é normal.
- No PowerShell, **não** use a variável `$home` (é reservada, `$HOME`) em scripts.
- URLs geradas são absolutas (`APP_URL=http://localhost:8000`); ao parsear HTML, procure
  `http://localhost:8000/noticia/...`, não caminhos relativos.
