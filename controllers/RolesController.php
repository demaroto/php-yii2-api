<?php

namespace app\controllers;

use app\rbac\ClientRule;
use app\rbac\UserRule;
use Yii;

class RolesController extends \yii\web\Controller
{

    private $roles = [];
    private $auth;

    public function actionIndex()
    {

        $request = Yii::$app->request;
        $this->auth = Yii::$app->authManager;
        $data = '';
        if ($request->get('create') == 1) {
            $data = 'Roles Criadas';

            $this->createRoles();
            $this->createClientPermission()
                ->createOrderPermission()
                ->createUserPermission()
                ->createProductPermission();
            }else if ($request->get('remove') == 1) {
                $this->removeRoles();
        }
        return $this->render('index', ['data' => $data]);
    }
    
    public function actionAdd($role, $id) {
        try {
            $this->auth = Yii::$app->authManager;
            $roleObj = $this->auth->getRole($role);
            $this->auth->assign($roleObj, $id);
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
        $data =  "Adicionado: " . $role . ' para: ' . $id;
        return $this->render('index', ['data' => $data]);
    }

    public function actionRemove($role, $id) {
        try {
            $this->auth = Yii::$app->authManager;
            $roleObj = $this->auth->getRole($role);
            $this->auth->revoke($roleObj, $id);
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
        $data =  "Removido: " . $role . ' de: ' . $id;
        return $this->render('index', ['data' => $data]);
    }

    private function createRoles()
    {
        //Roles
        $this->roles['admin'] = $this->auth->createRole('admin');
        $this->auth->add($this->roles['admin']);
        $this->roles['client'] = $this->auth->createRole('client');
        $this->auth->add($this->roles['client']);
        $this->roles['user'] = $this->auth->createRole('user');
        $this->auth->add($this->roles['user']);
    }

    private function removeRoles()
    {

        $this->auth->removeAll();
    }


    public function createProductPermission()
    {
        //Criar Produto
        $createProduct = $this->auth->createPermission('create-product');
        $createProduct->description = 'Cria um Produto';
        $this->auth->add($createProduct);
        //Editar Produto
        $editProduct = $this->auth->createPermission('edit-product');
        $editProduct->description = 'Edita um Produto';
        $this->auth->add($editProduct);
        //Visualiza Produto
        $viewProduct = $this->auth->createPermission('view-product');
        $viewProduct->description = 'Visualiza um Produto';
        $this->auth->add($viewProduct);
        //Visualiza Produto
        $deleteProduct = $this->auth->createPermission('delete-product');
        $deleteProduct->description = 'Apaga um Produto';
        $this->auth->add($deleteProduct);

        //Produto
        $this->auth->addChild($this->roles['admin'], $createProduct);
        $this->auth->addChild($this->roles['user'], $createProduct);

        $this->auth->addChild($this->roles['admin'], $editProduct);
        $this->auth->addChild($this->roles['user'], $editProduct);

        $this->auth->addChild($this->roles['admin'], $viewProduct);
        $this->auth->addChild($this->roles['user'], $viewProduct);
        $this->auth->addChild($this->roles['client'], $viewProduct);

        $this->auth->addChild($this->roles['admin'], $deleteProduct);
        return $this;
    }
    private function createOrderPermission()
    {
        //Criar Pedido
        $createOrder = $this->auth->createPermission('create-order');
        $createOrder->description = 'Cria um Pedido';
        $this->auth->add($createOrder);
        //Editar Pedido
        $editOrder = $this->auth->createPermission('edit-order');
        $editOrder->description = 'Edita um Pedido';
        $this->auth->add($editOrder);
        //Visualiza Pedido
        $viewOrder = $this->auth->createPermission('view-order');
        $viewOrder->description = 'Visualiza um Pedido';
        $this->auth->add($viewOrder);
        //Visualiza Pedido
        $deleteOrder = $this->auth->createPermission('delete-order');
        $deleteOrder->description = 'Apaga um Pedido';
        $this->auth->add($deleteOrder);

        //Pedido - Admin cria ordem
        $this->auth->addChild($this->roles['admin'], $createOrder);
        //Pedido - Admin edita ordem
        $this->auth->addChild($this->roles['admin'], $editOrder);
        //Pedido - Admin visualiza ordem
        $this->auth->addChild($this->roles['admin'], $viewOrder);
        //Pedido - Admin Apaga ordem
        $this->auth->addChild($this->roles['admin'], $deleteOrder);
        //Pedido - Usuário visualiza ordem
        $this->auth->addChild($this->roles['user'], $viewOrder);
        //Pedido - Cliente cria ordem
        $this->auth->addChild($this->roles['client'], $createOrder);

        //Instancia da Regra do Cliente
        $clientRule = new ClientRule();
        //Permissão de visualização da ordem para o proprio cliente
        $viewOwnerClient = $this->auth->createPermission('view-owner-client');
        $viewOwnerClient->description = 'Visualiza o próprio cliente';
        $viewOwnerClient->ruleName = $clientRule->name;
        $this->auth->add($viewOwnerClient);
        $this->auth->addChild($this->roles['client'], $viewOwnerClient);

        //Pedido - Cliente visualiza sua ordem
        $this->auth->addChild($viewOwnerClient, $viewOrder); //Apenas o próprio cliente visualiza sua ordem

        return $this;
    }

    private function createUserPermission()
    {
        $userRule = new UserRule();
        $this->auth->add($userRule);
        //Criar usuário
        $createUser = $this->auth->createPermission('create-user');
        $createUser->description = 'Cria um usuário';
        $this->auth->add($createUser);
        //Editar usuário
        $editUser = $this->auth->createPermission('edit-user');
        $editUser->description = 'Edita um usuário';
        $this->auth->add($editUser);
        //Visualiza usuário
        $viewUser = $this->auth->createPermission('view-user');
        $viewUser->description = 'Visualiza um usuário';
        $this->auth->add($viewUser);
        //Apaga usuário
        $deleteUser = $this->auth->createPermission('delete-user');
        $deleteUser->description = 'Apaga um usuário';
        $this->auth->add($deleteUser);
        //Visualiza o própio usuário
        $viewOwnerUser = $this->auth->createPermission('view-owner-user');
        $viewOwnerUser->description = 'Visualiza o próprio usuário';
        $viewOwnerUser->ruleName = $userRule->name;
        $this->auth->add($viewOwnerUser);
        //Editar o própio usuário
        $editOwnerUser = $this->auth->createPermission('edit-owner-user');
        $editOwnerUser->description = 'Edita o próprio usuário';
        $editOwnerUser->ruleName = $userRule->name;
        $this->auth->add($editOwnerUser);

        //Usuário
        $this->auth->addChild($this->roles['admin'], $createUser);
        $this->auth->addChild($this->roles['admin'], $editUser);
        $this->auth->addChild($this->roles['admin'], $viewUser);
        $this->auth->addChild($this->roles['admin'], $deleteUser);
        $this->auth->addChild($this->roles['user'], $viewOwnerUser);
        $this->auth->addChild($this->roles['user'], $editOwnerUser);
        $this->auth->addChild($viewOwnerUser, $viewUser);
        $this->auth->addChild($editOwnerUser, $editUser);

        return $this;
    }

    private function createClientPermission()
    {
        $clientRule = new ClientRule();
        $this->auth->add($clientRule);

        $editOwnerClient = $this->auth->createPermission('edit-owner-client');
        $editOwnerClient->description = 'Edita o próprio cliente';
        $editOwnerClient->ruleName = $clientRule->name;
        $this->auth->add($editOwnerClient);

        //Criar cliente
        $createClient = $this->auth->createPermission('create-client');
        $createClient->description = 'Cria um cliente';
        $this->auth->add($createClient);
        //Editar cliente
        $editClient = $this->auth->createPermission('edit-client');
        $editClient->description = 'Edita um cliente';
        $this->auth->add($editClient);

        //Visualiza cliente
        $viewClient = $this->auth->createPermission('view-client');
        $viewClient->description = 'Visualiza um cliente';
        $this->auth->add($viewClient);
        //Apaga cliente
        $deleteClient = $this->auth->createPermission('delete-client');
        $deleteClient->description = 'Apaga um cliente';
        $this->auth->add($deleteClient);
        //Cliente
        $this->auth->addChild($this->roles['admin'], $createClient);
        $this->auth->addChild($this->roles['admin'], $viewClient);
        $this->auth->addChild($this->roles['admin'], $editClient);
        $this->auth->addChild($this->roles['admin'], $deleteClient);

        $this->auth->addChild($this->roles['client'], $viewClient);
        $this->auth->addChild($this->roles['client'], $editOwnerClient);
        
        $this->auth->addChild($this->roles['user'], $viewClient);
        $this->auth->addChild($this->roles['user'], $editClient);

        $this->auth->addChild($editOwnerClient, $editClient);

        return $this;
    }
}
