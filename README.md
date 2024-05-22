### API Store - Yii2
![alt text](https://github.com/demaroto/php-yii2-api/blob/main/web/api-yii.jpg?raw=true)

Esta API foi desenvolvida em PHP, utilizando o framework Yii 2. Trata-se de um projeto com Authorization
Bearer Token
# Instalação
```javascript 1 - Docker Build
docker-compose build
```
```javascript 2 - Docker Run
docker-compose up -d
```

### Endpoints

# Autenticação
### POST    /api/auth

# Usuários
### POST    /api/users
### GET     /api/users
### GET     /api/users/:id
### PUT     /api/users/:id
### DELETE  /api/users/:id

# Clientes
### POST    /api/clientes
### GET     /api/clientes
### GET     /api/clientes/:id
### PUT     /api/clientes/:id
### GET     /api/clientes/produtos

# Produtos
### POST    /api/produtos
### GET     /api/produtos
### GET     /api/produtos/cliente?id=:cliente

# Documentação da API
Para mais detalhes da documentação, acesse a documentação utilizando o Postman [Documentação](https://documenter.getpostman.com/view/5545042/2sA3JGeimc)


