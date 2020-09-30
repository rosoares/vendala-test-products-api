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