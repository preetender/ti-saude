<p align="center"><a href="https://tisaude.com" target="_blank"><img src="https://app.tisaude.com/c/logo/10904_logo-ti.png" width="400"></a></p>

# Sobre



## Instalação

O primeiro passo é configurar as variáveis de ambiente de acordo com seu ambiente, no teste foi utilizado o `MySQL ^8.0` e `PHP 8.0.2` ou superior.

Duplique o arquivo reservado para as configurações da API como no comando a seguir em seu terminal:

> cp .env.example .env

Já em seu `.env` configure as variáveis abaixo com os dados do seu banco de dados.

### Banco de dados

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ti_saude
DB_USERNAME=user
DB_PASSWORD=password
```

Caso não tenha criado o banco, acesse o terminal:

> mysql -uUSER -p

Informe sua senha no passo a seguir e apos conectar, execute:

> CREATE DATABASE ti_saude

### composer

Execute  Instalação dos pacotes:

> composer install

### Banco de Dados

Após a configuração anterior, execute o comado para criar a estrutura do banco de dados + a geração de dados complementares.

> php artisan migrate --seed

### JWT

Para utilizar a autenticação via JWT após a configuração e executar a migração do banco de dados, execute:

> php artisan jwt:secret

O comando ira gerar uma chave secreta e um tipo de algoritmo para validação do token.

```env
JWT_SECRET=RANDOM_STRING

JWT_ALGO=HS256
```

### Iniciar Aplicação

No terminal execute o script a seguir para iniciar a aplicação, como no exemplo a seguir:

> php artisan serve --port=8080

Os recursos estaram disponiveis em: `http://localhost:8080`.

## Estrutura

Para fim de uma melhor organização, foi utilizado o modelo baseado nos principios do `Domain Drive Design`.

```
- Domain
    - Entity
        - Models
        - Observers
        - Repositories
        - ...
```

## Documentação

Navegue até `./public/docs`, na pasta tera dois arquivos um com endpoints no insomnia `ti-saude-endpoints.json` e outro com detalhes descritos no formator `swagger v2.0` `ti-saude.yaml`.

## Notas do desenvolvedor

O classe `App\Core\Repository` foi elaborada por mim a fim de facilitar o controle do dado, como podem notar, as rotas ligadas a `/api/v1/` possue um grupo dinamico chamado `/{model}/` isso é feito para evitar duplicação de codigo e assim tornar o código mais eficiente.

## Recursos Externos

- [preetender/laravel-finder](https://github.com/preetender/laravel-finder): pacote utilizado para montar consulta via `query-string` de minha autoria.

- [jubeki/laravel-code-style](https://github.com/jubeki/laravel-code-style): formatação de código.

## Teste

> php artisan test

## Formatação

Checar padrão do código e retornar diferença.

> composer run check-styles

Formar código de acordo com o padrão.

> composer run fix-style
