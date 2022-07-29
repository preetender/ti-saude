<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://app.tisaude.com/c/logo/10904_logo-ti.png" width="400"></a></p>

# Sobre



## Instalação

O primeiro passo é configurar as variáveis de ambiente de acordo com seu ambiente, no teste foi utilizado o `MySQL ^8.0` e `PHP 8.1` ou superior.

### JWT

Para utilizar a autenticação via JWT após a configuração e executar a migração do banco de dados, execute:

> php artisan jwt:secret

O comando ira gerar uma chave secreta e um tipo de algoritmo para validação do token.

```env
JWT_SECRET=RANDOM_STRING

JWT_ALGO=HS256
```
