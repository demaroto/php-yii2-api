<?php

namespace app\modules\api\controllers;

use app\modules\api\model\Cliente;
use app\modules\api\helpers\Response;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class ClientesController extends ActiveController
{
    public $modelClass = Cliente::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class
        ];
        $behaviors[] =  TimestampBehavior::class;
        return $behaviors;
    }

    public function actions()
    {
        parent::actions();
    }

    public function actionIndex()
    {
            $query = Cliente::find();
            $count = clone $query;
            $pages = new Pagination(['totalCount' => $count->count()]);
            $clientes = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
           
            $pagination = ['total' => $count->count(),'limit' => $pages->limit, 'links' => $pages->getLinks()];
            Response::JSON(200, $clientes, $pagination);


    }


    public function actionView($id)
    {
        $client = Cliente::findOne(['id' => $id]);
        if ($client){
             Yii::$app->user->can('edit-client', ['client' => $client->user->id]) ?
            Response::JSON(200, Cliente::findOne(['id' => $id])) :
            Response::JSON(401, 'Você não tem autorização para ver outros clientes');
        }else {
            Response::JSON(404, 'Cliente não encontrado.');
        }
    }

    public function actionUpdate($id)
    {
        $client = Cliente::findOne(['id' => $id]);
        if (!$client) 
             Response::JSON(404, 'Cliente não encontrado.');
        if (!Yii::$app->user->can('edit-client', ['client' => $client->user->id ?? null]))
             Response::JSON(401, 'Você não tem autorização para editar outro cliente.');

        $client->attributes = Yii::$app->request->post();
     
        //Salvar o update
        $client->save();
        Response::JSON(200, "Dados atualizados com sucesso.");
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->can('create-client'))
             Response::JSON(401, 'Você não tem autorização para criar um cliente');

        $client = new Cliente();
        $client->attributes = Yii::$app->request->post();
    
        if ($client->validate()) {
            $client->save();
            $this->addRole($client->id);
             Response::JSON(201, 'Cliente criado com sucesso');
        } else {
            foreach ($client->errors as $error) {
                $listtErrors[] = $error[0];
            }
             Response::JSON(400, $listtErrors[0]);
            
            
        }
    }

    public function actionProdutos()
    {
        $query = Cliente::find();
        $count = clone $query;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $clientes = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->joinWith('produtos')
            ->all();
       
        foreach($clientes as $key => $cliente) {

            $result[$key] = ['id' => $cliente->id, 'nome' => $cliente->nome, 'produtos' => $cliente->produtos];

        }
     
        $pagination = ['total' => $count->count(),'limit' => $pages->limit, 'links' => $pages->getLinks()];
        Response::JSON(200, $result, $pagination);
    }

    private function addRole($userId, $role = 'client')
    {
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($role), $userId);
    }

}
