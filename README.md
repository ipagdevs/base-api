# BASE API

## Instalação

### Clone o projeto do GitHub

```ssh
git clone git@github.com:ipagdevs/base-api.git
```
### Após clonar o projeto é necessário rodar o composer update

> Importante que o PHP seja pelo menos 7.1 ou posterior.

```ssh
composer update
```
### Configurando as variáveis de ambiente (.env)

Aqui é necessário copiar o arquivo .env.example para .env, realize as alterações (principalmente referente ao banco de dados).

```ssh
cp .env.example .env
```
### Criar a APP_KEY

```ssh
php artisan key:generate
```
> Isto irá gerar uma chave segura para ser utilizada no processo de criptografia dos comandos da API.

### Migrations

> O mais importante das migrations são as migrations do Passport (OAuth) e a 'user_api_tokens', talvez seja necessário adaptar essa migration a sua realidade. Principalmente caso seu banco de dados não possua a tabela User.

```ssh
> Caso já possua um banco de dados
php artisan migrate --seed

> Caso esteja criando um banco de dados limpo
php artisan migrate:fresh --seed
```

### Comandos Artisan

> Os comandos a seguir não são obrigatórios, porém ajudam bastante no processo de criar novos recursos (classes) para o seu projeto.

#### Novo Model

```
php artisan make:model Models/`<NomeDoSeuModel>`
```

#### Novo Controller


```
> Controlador comum
php artisan make:controller API/`<NomeDoSeuController>`

> Controlador de API
php artisan make:controller API/`<NomeDoSeuController>` --api

> Controlador de API com Model
php artisan make:controller API/`<NomeDoSeuController>` --model=`<NomeDoSeuModel>` --api
```

> O Controller de API já irá ser criado com as Actions: index, show, store, update e destroy, utilizadas respectivamente para listar itens, recuperar itens, gravar, atualizar e apagar.

#### Novo Resource

```
> Resource Simples
php artisan make:resource `<NomeDoSeuResource>`

> Resource de Collection
php artisan make:resource `<NomeDoSeuResource>` --collection
```

#### Novo Request
```
php artisan make:request `<NomeDoSeuRequest>`
```

## Autenticação

### Gerando a API Token de um Usuário

Envie um POST para /v1/user/{id}/generateToken
Isto irá gerar um api_token e irá devolver essa informação no Response.

### Autenticação OAuth

Para que a autenticação funcione é necessário gerar uma API Token para seu usuário, feito isso ele irá utilizar o endpoint /v1/auth enviando o seguinte:

```json
{
    "api_id": "api_id gerado para o usuário",
    "api_token": "api_token gerado para o usuário",
}
```

Feito isso será devolvido um Token OAuth que tem duração de 1 ano. Caso queira alterar esse tempo basta alterar a variável de ambiente OAUTH_TOKEN_LIFETIME informado em quantos minutos o token deverá expirar.

