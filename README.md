
# Desafio 2 - Backend

Requisitos:
* PHP >= 8.2
* MySQL


## Instalação

Criar um arquivo .env e copiar o conteúdo do .env.example para .env

```bash
cp .env.example .env
```

Configurar o arquivo .env com as configurações do banco de dados

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sicredi
DB_USERNAME=root
DB_PASSWORD=
```

É necessário criar o banco de dados manualmente com o mesmo nome do DB_DATABASE do do arquivo .env.

Depois instalar as dependencias:


```bash
composer install
```
Gerar a secret key do projeto:

```bash
php artisan key:generate
```
Migrar o banco de dados (Criar as tabelas)

```bash
php artisan migrate
```

Colocar rodar a API

```bash
php artisan serve
```
## Documentação da API

#### Registrar usuário

```http
POST /api/auth/register
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `name` | `string` | **Nome do usuário**.  |
| `email` | `string` | **Email do usuário**.  |
| `password` | `string` | **Senha do usuário**.  |

#### Login

```http
POST /api/auth/login
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `email` | `string` | **Email do usuário**.  |
| `password` | `string` | **Senha do usuário**.  |



