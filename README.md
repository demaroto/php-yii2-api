### API Store - Yii2
![alt text](https://github.com/demaroto/php-yii2-api/blob/main/web/api-yii.jpg?raw=true)

Esta API foi desenvolvida em PHP, utilizando o framework Yii 2. Trata-se de um projeto com Authorization
Bearer Token
# Instalação
###  Realizar build da api 
```javascript Docker Build
docker-compose build
```
### Subir os containers MYSQL + API + NGINX
```javascript Docker Run
docker-compose up -d
```
### Executar as migrações do banco
```javascript Migrations
php yii migrate
```
```javascript Migrations 2
php yii migrate --migrationPath=@yii/rbac/migrations
```
### Habilitar regras de autorização - Apenas na primeira vez
[Autorização](http://localhost:8080/roles?create=1)
[Criar Usuário Admin](http://localhost:8080/roles/add?user=1&role=admin)

# Documentação da API
Para mais detalhes da documentação, acesse a documentação utilizando o Postman [Documentação](https://documenter.getpostman.com/view/5545042/2sA3JGeimc)


