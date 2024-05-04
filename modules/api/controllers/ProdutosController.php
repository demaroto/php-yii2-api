<?php

namespace app\modules\api\controllers;

use app\modules\api\model\Produto;
use yii\data\Pagination;
use \yii\rest\ActiveController;
use app\modules\api\helpers\Response;
use app\modules\api\model\Cliente;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\filters\auth\HttpBearerAuth;

class ProdutosController extends ActiveController
{
    public $modelClass = Produto::class;

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
        $query = Produto::find();
        $count = clone $query;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $produtos = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $pagination = ['total' => $count->count(), 'limit' => $pages->limit, 'links' => $pages->getLinks()];
        Response::JSON(200, $produtos, $pagination);
    }

    public function actionCreate()
    {
        $produto = new Produto();
        $cliente = Cliente::findOne(['user_id' => Yii::$app->user->id]);
        $produto->attributes = Yii::$app->request->post();
        if (!$cliente)
            return Response::JSON(404, 'Usuário não é cliente. Verifique o token.');
        
        $produto->cliente_id = $cliente->id;

        if ($produto->validate()) {
            $produto->save();
            return Response::JSON(201, $produto);
        }else{
            foreach ($produto->errors as $error) {
                $listErrors[] = $error[0];
            }
             return Response::JSON(400, $listErrors[0]);
        }
    }

    public function actionCliente($id)
    {
        $query = Produto::find()->where(['cliente_id' => $id]);
        $count = clone $query;
        if($count->count() == 0)
            return Response::JSON(404, 'Cliente não encontrado.');
        
        $pages = new Pagination(['totalCount' => $count->count()]);
        $produtos = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $pagination = ['total' => $count->count(), 'limit' => $pages->limit, 'links' => $pages->getLinks()];
        return Response::JSON(200, $produtos, $pagination);
    }
}
