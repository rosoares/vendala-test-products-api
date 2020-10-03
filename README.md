# Teste Venda.la API de Produtos

## Instalação:

1. Clonar o repositório: `$ git clone https://github.com/rosoares/vendala-test-products-api.git`
2. Entrar no repositório `$ cd  vendala-test-products-api` e executar o comando> `$ composer install`
3. Gerar a chave da aplicação `php artisan key:generate`
4. Gerar a chave do JWT `php artisan jwt:secret`
5. Crie o arquivo **.env** (você pode utilizar o arquivo **.env.example** como ponto de partida)
6. Edite as variáveis de conexão com o banco de dados:
    `DB_HOST=[host do seu MySQL]`
    `DB_PORT=[porta do seu MySQL]`
    `DB_DATABASE=[nome do banco que você criou]`
    `DB_USERNAME=[seu usuário do MySQL]`
    `DB_PASSWORD=[sua senha de acesso ao MySQL]`
7. Rode o comando `php artisan migrate`
8. Rode o comando `php artisan colors:get` para pegar as cores da API pública do mercado livre

## Utilização
### Cadastro de usuário

Requisição: POST /api/users
```json
{
    "name": "Rodrigo Soares",
    "email": "rodrigosoares8899@gmail.com",
    "password": "strongpassword!@#$",
    "password_confirmation": "strongpassword!@#$"
}
```
Resposta:
```json
{
    "name": "Rodrigo Soares",
    "email": "rodrigosoares8899@gmail.com",
    "updated_at": "2020-09-30T13:51:54.000000Z",
    "created_at": "2020-09-30T13:51:54.000000Z",
    "id": 1
}
```

------------

### Login
Requisição: POST /api/auth/login
```json
{
    "email": "rodrigosoares8899@gmail.com",
    "password": "strongpassword!@#$"
}
```
Resposta:
```json
{
"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYwMTUwNDY1MSwiZXhwIjoxNjAxNTA4MjUxLCJuYmYiOjE2MDE1MDQ2NTEsImp0aSI6IlhXSEcxT09MSVEzYkRBQ2ciLCJzdWIiOjEyLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.EfEN-Veh_ZJmJLAUhT1FSpzCFXLy1j2DtKywaIeLDvg",
    "token_type": "bearer",
    "expires_in": 3600
}
```

------------

## Produtos
*NOTA: Todos os endpoints de produtos são protegidos e requerem autenticação, não esqueça de adicionar o `access_token` obtido no login*

### Cadastro de Produtos sem variação
Requisição: POST /api/products
```json
{
	"name" : "Tênis de Basquete Nike",
	"description" : "Tênis de Basquete",
	"slug" : "tenis-basquete-nike",
	"first_stock" : 100,
	"available_stock" : 100,
	"price" : 369.90,
	"hasColorVariation" : false
}
```
Resposta:
```json
{
    "name": "Tênis de Basquete Nike",
    "description": "Tênis de Basquete",
    "slug": "tenis-basquete-nike",
    "created_by": 1,
    "updated_at": "2020-10-03T03:06:59.000000Z",
    "created_at": "2020-10-03T03:06:59.000000Z",
    "id": 1,
    "color_variations": [
        {
            "id": 1,
            "product_id": 1,
            "color_id": null,
            "first_stock": 100,
            "available_stock": 100,
            "price": "369.90",
            "created_at": "2020-10-03T03:06:59.000000Z",
            "updated_at": "2020-10-03T03:06:59.000000Z",
            "deleted_at": null,
			"color": null
        }
    ]
}
```
### Cadastro de Produtos com variação
Requisição: POST /api/products
```json
Requisição: POST /api/products
{
    "name":"Camisa Nike",
    "description":"Camisa Nike Algodão",
    "slug":"camisa-nike",
    "hasColorVariation":true,
    "variations":[
        {
            "color_id":51993,
            "first_stock":50,
            "available_stock":50,
            "price":99.9
        },
        {
            "color_id":51993,
            "first_stock":60,
            "available_stock":60,
            "price":97.9
        }
    ]
}
```
Resposta:
```json
[
    {
    "name": "Camisa Nike",
    "description": "Camisa Nike Algodão",
    "slug": "camisa-nike",
    "created_by": 5,
    "updated_at": "2020-10-03T15:36:57.000000Z",
    "created_at": "2020-10-03T15:36:57.000000Z",
    "id": 1,
    "color_variations": [
        {
            "id": 1,
            "product_id": 1,
            "color_id": 51993,
            "first_stock": 50,
            "available_stock": 50,
            "price": "99.90",
            "created_at": "2020-10-03T15:36:57.000000Z",
            "updated_at": "2020-10-03T15:36:57.000000Z",
            "deleted_at": null,
            "color": {
                "id": 51993,
                "name": "Vermelho",
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        },
        {
            "id": 2,
            "product_id": 1,
            "color_id": 51993,
            "first_stock": 60,
            "available_stock": 60,
            "price": "97.90",
            "created_at": "2020-10-03T15:36:57.000000Z",
            "updated_at": "2020-10-03T15:36:57.000000Z",
            "deleted_at": null,
            "color": {
                "id": 51993,
                "name": "Vermelho",
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        }
    }
]
```

------------

### Atualização de Produtos sem variação de cores
Requisição PUT /api/products/:id_do_produto
```json
{
    "name":"Tênis Nike Precision",
    "description":"Camisa Nike Algodão",
    "slug":"tenis-nike-precision",
    "first_stock": 100,
    "available_stock": 60,
    "price": 469.90,
    "hasColorVariation":false
}
```
Resposta:
```json
{
    "id": 1,
    "name": "Tênis Nike Precision V",
    "description": "Camisa Nike Algodão",
    "slug": "tenis-nike-precision-iv",
    "created_by": 3,
    "created_at": "2020-10-03T04:50:07.000000Z",
    "updated_at": "2020-10-03T13:41:35.000000Z",
    "deleted_at": null,
    "color_variations": [
        {
            "id": 1,
            "product_id": 1,
            "color_id": null,
            "first_stock": 100,
            "available_stock": 60,
            "price": "469.90",
            "created_at": "2020-10-03T04:50:07.000000Z",
            "updated_at": "2020-10-03T13:41:35.000000Z",
            "deleted_at": null,
			"color": null
        }
    ]
}
```

### Atualização de Produtos com variação de cores
Requisição PUT /api/products/:id_do_produto
```json
{
    "name":"Camisa Nike Fly",
    "description":"Camisa Nike Fly Algodão",
    "slug":"camisa-nike",
    "hasColorVariation":true,
    "variations":[
        {
            "color_id":51993,
            "first_stock":50,
            "available_stock":49,
            "price":89.9
        },
        {
            "color_id":51993,
            "first_stock":60,
            "available_stock":50,
            "price":97.9
        }
    ]
}
```
Resposta:
```json
{
    "id": 1,
    "name": "Camisa Nike",
    "description": "Camisa Nike Algodão",
    "slug": "camisa-nike",
    "created_by": 5,
    "created_at": "2020-10-03T15:36:57.000000Z",
    "updated_at": "2020-10-03T15:36:57.000000Z",
    "deleted_at": null,
    "color_variations": [
        {
            "id": 1,
            "product_id": 1,
            "color_id": 51993,
            "first_stock": 50,
            "available_stock": 39,
            "price": "89.90",
            "created_at": "2020-10-03T15:36:57.000000Z",
            "updated_at": "2020-10-03T15:39:24.000000Z",
            "deleted_at": null,
            "color": {
                "id": 51993,
                "name": "Vermelho",
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        },
        {
            "id": 2,
            "product_id": 1,
            "color_id": 51993,
            "first_stock": 60,
            "available_stock": 60,
            "price": "97.90",
            "created_at": "2020-10-03T15:36:57.000000Z",
            "updated_at": "2020-10-03T15:36:57.000000Z",
            "deleted_at": null,
            "color": {
                "id": 51993,
                "name": "Vermelho",
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        },
        {
            "id": 3,
            "product_id": 1,
            "color_id": 52008,
            "first_stock": 60,
            "available_stock": 45,
            "price": "49.90",
            "created_at": "2020-10-03T15:39:24.000000Z",
            "updated_at": "2020-10-03T15:39:24.000000Z",
            "deleted_at": null,
            "color": {
                "id": 52008,
                "name": "Creme",
                "created_at": null,
                "updated_at": null,
                "deleted_at": null
            }
        }
    ]
}
```
*NOTA: Se a variação de cor existir ela será atualizada, caso não exista será criada uma nova*


------------

### Listagem de todos os Produtos
Requisição GET /api/products

Resposta:
```json
[
    {
        "id": 1,
        "name": "Camisa Nike",
        "description": "Camisa Nike Algodão",
        "slug": "camisa-nike",
        "created_by": 5,
        "created_at": "2020-10-03T15:36:57.000000Z",
        "updated_at": "2020-10-03T15:36:57.000000Z",
        "deleted_at": null,
        "color_variations": [
            {
                "id": 1,
                "product_id": 1,
                "color_id": 51993,
                "first_stock": 50,
                "available_stock": 39,
                "price": "89.90",
                "created_at": "2020-10-03T15:36:57.000000Z",
                "updated_at": "2020-10-03T15:39:24.000000Z",
                "deleted_at": null,
                "color": {
                    "id": 51993,
                    "name": "Vermelho",
                    "created_at": null,
                    "updated_at": null,
                    "deleted_at": null
                }
            },
            {
                "id": 2,
                "product_id": 1,
                "color_id": 51993,
                "first_stock": 60,
                "available_stock": 60,
                "price": "97.90",
                "created_at": "2020-10-03T15:36:57.000000Z",
                "updated_at": "2020-10-03T15:36:57.000000Z",
                "deleted_at": null,
                "color": {
                    "id": 51993,
                    "name": "Vermelho",
                    "created_at": null,
                    "updated_at": null,
                    "deleted_at": null
                }
            },
            {
                "id": 3,
                "product_id": 1,
                "color_id": 52008,
                "first_stock": 60,
                "available_stock": 45,
                "price": "49.90",
                "created_at": "2020-10-03T15:39:24.000000Z",
                "updated_at": "2020-10-03T15:39:24.000000Z",
                "deleted_at": null,
                "color": {
                    "id": 52008,
                    "name": "Creme",
                    "created_at": null,
                    "updated_at": null,
                    "deleted_at": null
                }
            }
        ]
    }
]
```
### Listagem de produto
Requisição GET /api/products/id_do_produto/id_da_variacao

Resposta: 
```json
{
    "id": 2,
    "name": "Camisa Nike Fly",
    "description": "Camisa Nike Fly Algodão",
    "slug": "camisa-nike",
    "created_by": 4,
    "created_at": "2020-10-03T04:50:07.000000Z",
    "updated_at": "2020-10-03T14:18:10.000000Z",
    "deleted_at": null,
    "color_variations": [
        {
            "id": 4,
            "product_id": 2,
            "color_id": 51993,
            "first_stock": 50,
            "available_stock": 39,
            "price": "89.90",
            "created_at": "2020-10-03T14:18:10.000000Z",
            "updated_at": "2020-10-03T14:22:19.000000Z",
            "deleted_at": null,
			"color": {
                    "id": 51993,
                    "name": "Vermelho",
                    "created_at": null,
                    "updated_at": null,
                    "deleted_at": null
                }
        }
    ]
}
```
*NOTA: caso não seja passado o id_da_variacao o endpoint retornará o produto sem variação*

------------

### Exclusão de produto
Requisição DELETE /api/products/:id_do_produto
*NOTA: O status code esperado na requisição é 204*

### Exclusão de variação do produto
Requisição DELETE api/products/variations/:id_da_variacao
*NOTA: O status code esperado na requisição é 204*