# Checkout App


## Para testar o projeto:
1. Certifique-se de ter o PHP e o Composer instalados em sua máquina.
2. Clone o repositório em sua máquina.
3. Abra o terminal e acesse a pasta do projeto.
4. Execute o comando "composer install" para instalar as dependências do projeto.
5. Suba o docker-compose para iniciar o banco de dados e o redis
6. Execute o comando "php artisan migrate" para criar as tabelas no banco de dados.
7. Execute o comando "php artisan serve" para iniciar o servidor.
8. Acesse o endereço http://localhost:8000 no seu navegador para acessar o projeto.
9. Faça login ou crie uma conta para começar a gerenciar
10. Link do login: http://localhost:8000/login
11. Link para criar uma conta: http://localhost:8000/register

## Rotas usadas no projeto

 - http://localhost:8000 = Página de produtos
 - http://localhost:8000/login = Página de login do vendedor
 - http://localhost:8000/register = Página de registro do vendedor
 - http://localhost:8000/products = Página de produtos do vendedor
 - http://localhost:8000/orders = Página de pedidos do vendedor


## Usuário de teste:
Email: vendedor@teste.com
Senha: senha123

## Tecnologias (stack):
- Docker
- PHP (Laravel 12)
- Vue3 + InertiaJs
- PostgreSQL
- Redis
- Tailwindcss
- Laravel Breeze

## Imagens
### Tela de produtos
![image](https://github.com/user-attachments/assets/9c4e387f-a8a1-4321-9d9b-44cd74d3e082)
### Pedidos Recebidos
![image](https://github.com/user-attachments/assets/ca119614-f446-4c5a-a00b-b53b65394f4f)
### CRUD de Produtos
![image](https://github.com/user-attachments/assets/1d145c66-20f8-4eb6-9de9-9a734fc616d9)
### Dash
![image](https://github.com/user-attachments/assets/56f7ac0d-7823-409a-afd2-9d14b4a3fc96)
### Novo produto
![image](https://github.com/user-attachments/assets/84564e7a-ad0f-4eac-9bd1-e4af031cc631)


