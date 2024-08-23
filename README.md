# Api CRUD de Produtos

Uma API CRUD para Produtos em Laravel

Documentação: [API Products](https://www.postman.com/myapisdev/workspace/api-s-dev/documentation/17224712-85874a7c-e078-432c-b8d8-27a59635ac77)

Postman: [Collection](https://www.postman.com/myapisdev/workspace/api-s-dev/collection/17224712-85874a7c-e078-432c-b8d8-27a59635ac77?action=share&source=copy-link&creator=17224712)
## Configuração


### Versão Laravel

API compatível com versão 9.52.16 +

### Configurando o ambiente e o projeto 

Primeiramente, localize o arquivo .env na raiz do projeto. Altere as informações de conexão com o banco de dados de acordo com o seu database:

```xml

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=products
DB_USERNAME=
DB_PASSWORD=

```

Na raíz do projeto execute o comando:

```xml
php artisan migrate
```
Este comando criará o banco de dados e as tabelas necessárias para a execução e funcionamento da API.

Feito isso, execute o seguinte comando também na raiz do projeto. Ele é responsavel por criar um usuário para testes dos endpoints. Com este usuário você irá se autenticar e fazer as requisições para a API.

```xml
php artisan db:seed --class=UsersTableSeeder
```
Para testes, um usuário padrão será criado e poderá ser usado para autenticação. 

```json
{
    "email": "user@gmail.com",
    "password": "user123"
}
```

### Pronto! Seu ambiente e projeto está totalmente configurado. Agora, leia a documentação para consumir os Endpoints da API.
