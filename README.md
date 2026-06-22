# Portal de Notícias (Laravel)

Portal de notícias com painel administrativo multiusuário, construído em **Laravel 13**,
**PHP 8.3+**, **MySQL/MariaDB**, **Blade + Alpine.js**, **Tailwind CSS 4 (Vite)**,
**TipTap 3** (editor) e **HTMLPurifier** (sanitização). Pensado para rodar em
**hospedagem compartilhada (cPanel — HostGator)**: sem workers, sem Redis, agendamento
por Cron Job.

## Stack

| Camada            | Tecnologia                                  |
| ----------------- | ------------------------------------------- |
| Framework         | Laravel 13 (rotas + Blade, SSR)             |
| Linguagem         | PHP 8.3+                                     |
| Banco             | MySQL / MariaDB (Eloquent + migrations)     |
| Autenticação      | Sessão nativa do Laravel (e-mail/senha)     |
| UI admin          | Blade + Alpine.js                           |
| Editor            | TipTap 3 (via Vite)                         |
| Estilização       | Tailwind CSS 4 (Vite)                       |
| Validação         | Form Requests                               |
| Hash de senha     | bcrypt (`Hash::make`)                        |
| Sanitização HTML  | HTMLPurifier (iframe só do YouTube)         |
| Cache/Sessão/Fila | `database` (sem Redis)                       |

## Papéis de acesso

- **ADMIN** — acesso total: usuários, categorias, qualquer notícia, anúncios, configurações, logs.
- **EDITOR** — cria/edita/publica qualquer notícia; categorias; modera comentários; estatísticas.
- **AUTHOR** — cria/edita apenas as próprias notícias; envia para revisão; não publica.

O papel é verificado **no servidor em cada ação** (Policies/Gates + middleware), não só na UI.

## Funcionalidades

- Site público (SSR): home (postagem fixa, manchete, "mais lidas"), notícia (contador de
  visualizações, "leia também", comentários, vídeos do YouTube), categoria, tag, autor e busca.
- SEO: meta tags, **Open Graph**, **Twitter Card**, **JSON-LD `NewsArticle`**, `sitemap.xml`,
  `robots.txt`, **RSS** (`/feed.xml`) e `ads.txt`.
- Admin: CRUD de notícias (editor rich text, upload de capa, tags, agendamento, fixar),
  categorias, usuários; fila de **revisão** (publicar/devolver com comentário).
- Comentários com **moderação**; **anúncios** próprios (impressões/cliques/relatório com CTR);
  **Google AdSense**; **estatísticas**; **log de auditoria**; **configurações** (nome/logo/favicon).
- Segurança: senhas com bcrypt, validação de entradas, sanitização de HTML, rate limit
  (login e comentários, 5/min/IP), confirmação de senha em ações sensíveis, headers de segurança.
- **Agendamento** de publicação por comando Artisan + scheduler (Cron Job do cPanel).
- **Storage plugável** (`public` local ou `s3`) e **e-mail** por SMTP (produção) ou `log` (dev).

---

## Pré-requisitos

- PHP **8.3+** com as extensões: `mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json,
  bcmath, fileinfo, curl, gd`.
- Composer 2.
- Node.js 20+ e npm (apenas para **compilar os assets**; não é necessário no servidor).
- MySQL/MariaDB.

## Setup local

```bash
# 1. Dependências
composer install
npm install

# 2. Ambiente
cp .env.example .env
php artisan key:generate
# edite o .env: DB_*, SEED_ADMIN_*, etc.

# 3. Banco
php artisan migrate
php artisan db:seed          # admin inicial + categorias + posts de exemplo

# 4. Assets + storage
npm run build                # (ou: npm run dev, para hot reload)
php artisan storage:link

# 5. Servir
php artisan serve
```

Acesse `http://localhost:8000` (site) e `http://localhost:8000/admin` (painel).
Login inicial: `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` do `.env` (padrão
`admin@noticias.local` / `admin12345` — **troque em produção**).

> **Windows (Laragon):** abra o Laragon e "Start All" para subir o MySQL antes dos comandos.

## Variáveis de ambiente principais

| Variável | Descrição |
| --- | --- |
| `APP_NAME` / `APP_URL` | Nome e URL pública do site |
| `APP_ENV` / `APP_DEBUG` | `production` / `false` em produção |
| `DB_*` | Conexão MySQL |
| `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` | Admin criado pelo seed |
| `UPLOAD_DISK` | `public` (local) ou `s3` |
| `AWS_*` | Credenciais/bucket S3 (quando `UPLOAD_DISK=s3`; suporta R2/Spaces/MinIO via `AWS_ENDPOINT`) |
| `MAIL_*` | SMTP em produção (recuperação de senha); `MAIL_MAILER=log` em dev |

## Scripts úteis

| Comando | Descrição |
| --- | --- |
| `php artisan serve` | Servidor de desenvolvimento |
| `npm run dev` / `npm run build` | Assets (dev / produção) |
| `php artisan migrate` / `migrate --force` | Migrations (dev / produção) |
| `php artisan db:seed` | Popular o banco |
| `php artisan posts:publish-scheduled` | Publica agendamentos vencidos (roda pelo scheduler) |
| `php artisan test` | Testes (Pest/PHPUnit) |

---

## Deploy na HostGator (cPanel, com SSH)

> O plano compartilhado roda PHP + MySQL. **Não há Node no servidor** — os assets são
> compilados localmente e enviados. O `vendor/` é instalado por Composer no servidor (via SSH).

### 1. Banco de dados (cPanel -> MySQL Databases)

Crie um banco e um usuário, associe-os (todos os privilégios) e anote as credenciais.

### 2. Estrutura de pastas (document root = `public/` do Laravel)

O cPanel serve `public_html`. **Não** coloque o Laravel inteiro dentro de `public_html`.
Recomendado: subir o projeto para **fora** de `public_html` e apontar o domínio para a
pasta `public` do Laravel.

```
/home/usuario/
  laravel/                 <- projeto (app, config, vendor, ...)
    public/                <- raiz pública real
  public_html/             <- (ver opção A ou B)
```

- **Opção A (recomendada):** no cPanel, em *Domains*, defina o **Document Root** do domínio
  para `/home/usuario/laravel/public`.
- **Opção B (sem mudar o document root):** copie o conteúdo de `public/` para `public_html/`
  e ajuste, em `public_html/index.php`, os caminhos de `require` para apontar a
  `../laravel/vendor/autoload.php` e `../laravel/bootstrap/app.php`.

### 3. Enviar o código

Compile os assets **localmente** e envie tudo (Git ou FTP):

```bash
npm run build                                   # gera public/build (commit/envie)
```

`.env`, `vendor/`, `node_modules/`, `public/build` e `public/storage` estão no `.gitignore`
do projeto — envie `public/build` manualmente ou ajuste o `.gitignore` no deploy. As
dependências PHP são instaladas no servidor (passo 5).

### 4. `.env` de produção

Crie o `.env` no servidor (a partir do `.env.example`):

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br
APP_KEY=                      # gere no passo 5
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=usuario_db
DB_USERNAME=usuario_user
DB_PASSWORD=senha
UPLOAD_DISK=public
MAIL_MAILER=smtp              # e-mail da HostGator ou externo
MAIL_HOST=mail.seudominio.com.br
MAIL_PORT=465
MAIL_USERNAME=no-reply@seudominio.com.br
MAIL_PASSWORD=...
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=no-reply@seudominio.com.br
```

### 5. Instalar e preparar (via SSH)

```bash
cd ~/laravel
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force          # apenas na primeira vez (cria o admin)
php artisan storage:link

# Caches de produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Permissões (se necessário): `storage/` e `bootstrap/cache/` precisam ser graváveis
(geralmente `755`/`775`).

### 6. Cron Job (publicação agendada)

Em cPanel -> *Cron Jobs*, adicione um job **a cada minuto**:

```
* * * * * /usr/local/bin/php /home/usuario/laravel/artisan schedule:run >> /dev/null 2>&1
```

(Confirme o caminho do PHP no seu plano — pode ser `/opt/cpanel/ea-php83/root/usr/bin/php`.)
Isso aciona o scheduler, que executa `posts:publish-scheduled` a cada minuto.

### 7. SSL e e-mail

- Ative o **SSL** (Let's Encrypt via cPanel) e use `https://` em `APP_URL`.
- Configure o **e-mail SMTP** (HostGator ou externo) para a recuperação de senha funcionar.

### 8. Uploads

Os uploads ficam em `storage/app/public/uploads` (servidos via `public/storage`).
**Garanta que essa pasta persista entre deploys** e não seja sobrescrita.
Para escalar/economizar cota, troque `UPLOAD_DISK=s3` e configure as `AWS_*`.

### Atualizações posteriores

```bash
git pull            # ou envie os arquivos
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

> Após enviar novos assets, lembre de rodar `npm run build` localmente e enviar `public/build`.

---

## Testes

```bash
php artisan test
```

Cobrem: autenticação e papéis, site público + SEO, CRUD do admin, sanitização,
comentários, anúncios, configurações, agendamento, headers de segurança, slug e formatação.
