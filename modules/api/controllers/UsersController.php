<?php

namespace app\modules\api\controllers;

use app\models\User;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class UsersController extends ActiveController
{

    public $modelClass = User::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class
        ];
        return $behaviors;
    }

    public function actions()
    {
        parent::actions();
    }

    public function actionIndex()
    {
        return Yii::$app->user->can('view-user') ?
            $this->responseJson(200, User::find()->all()) :
            $this->responseJson(401, 'Você não tem autorização para visualizar os usuários');
    }

    public function actionView($id)
    {
        return Yii::$app->user->can('view-user', ['user' => $id]) ?
            $this->responseJson(200, User::findOne(['id' => $id])) :
            $this->responseJson(401, 'Você não tem autorização para visualizar este usuário');
    }

    public function actionCreate()
    {

        if (!Yii::$app->user->can('create-user'))
            return $this->responseJson(401, 'Você não tem autorização para criar um usuário');

        $auth = Yii::$app->authManager;
        $newUser = new User();

        $newUser->nome = Yii::$app->request->post('nome');
        $newUser->email = Yii::$app->request->post('email');
        $newUser->password = Yii::$app->request->post('password');
        if ($newUser->validate()) {
            $newUser->setPassword(Yii::$app->request->post('password'));
            $newUser->save();
            $this->addRoleToUser($newUser->id);
            $this->responseJson(201, $newUser);
        } else {
            foreach ($newUser->errors as $error) {
                $listtErrors[] = $error[0];
            }
            $this->responseJson(400, $listtErrors[0]);
        }
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('edit-user', ['user' => $id]))
            return $this->responseJson(401, 'Você não tem autorização para editar outro usuário.');

        $user = User::findOne(['id' => $id]);
        $user->attributes = Yii::$app->request->post();
        if ($user->validate()) {
            $user->save();
            return $this->responseJson(200, "Usuário editado com sucesso!");
        } else {
            foreach ($user->errors as $error) {
                $listtErrors[] = $error[0];
            }
            return $this->responseJson(400, $listtErrors[0]);
        }
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete-user'))
            return $this->responseJson(401, 'Você não tem autorização para apagar um usuário.');
        $user = User::findOne(['id' => $id]);
        if (!$user) return $this->responseJson(404, 'Usuário não encontrado.');
        User::findOne(['id' => $id])->delete();
        return $this->responseJson(204);
    }

    private function addRoleToUser($userId, $role = 'user')
    {
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($role), $userId);
    }

    private function responseJson($statusCode, $message = "")
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->statusCode = $statusCode;
        $hasError = $statusCode < 400 ? false : true;
        $statusMessage = $hasError ? 'error' : 'success';
        $response->data = ['status' => $statusMessage, 'data' => $message];
    }
}
