<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\modules\api\helpers\Response;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class UsersController extends ActiveController
{

    public $modelClass = User::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['create']
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
        return Yii::$app->user->can('view-user') ?
            Response::JSON(200, User::find()->where(['status' => 1])->all()) :
            Response::JSON(401, 'Você não tem autorização para visualizar os usuários');
    }

    public function actionView($id)
    {
        if (!Yii::$app->user->can('view-user', ['user' => $id])) 
            Response::JSON(401, 'Você não tem autorização para visualizar este usuário');
            $user = User::findOne(['id' => $id, 'status' => 1]);
            $user ? Response::JSON(200, User::findOne($user)) :  Response::JSON(404, 'Usuário não encontrado.');
    }

    public function actionCreate()
    {

        $newUser = new User();

        $newUser->nome = Yii::$app->request->post('nome');
        $newUser->email = Yii::$app->request->post('email');
        $newUser->password = Yii::$app->request->post('password');
       
        if ($newUser->validate()) {
            $newUser->setPassword(Yii::$app->request->post('password'));
            $newUser->save();
   
            Response::JSON(201, $newUser);
        } else {
            foreach ($newUser->errors as $error) {
                $listtErrors[] = $error[0];
            }
            Response::JSON(400, $listtErrors[0]);
        }
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('edit-user', ['user' => $id]))
            return Response::JSON(401, 'Você não tem autorização para editar outro usuário.');

        $user = User::findOne(['id' => $id]);
        $user->attributes = Yii::$app->request->post();
        if ($user->validate()) {
            $user->save();
            return Response::JSON(200, "Usuário editado com sucesso!");
        } else {
            foreach ($user->errors as $error) {
                $listtErrors[] = $error[0];
            }
            return Response::JSON(400, $listtErrors[0]);
        }
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete-user'))
            return Response::JSON(401, 'Você não tem autorização para apagar um usuário.');
        $user = User::findOne(['id' => $id]);
        if (!$user) return Response::JSON(404, 'Usuário não encontrado.');
        $user->status = 0;
        $user->save();
        return Response::JSON(204);
    }
    
}
