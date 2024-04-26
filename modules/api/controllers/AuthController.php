<?php

namespace app\modules\api\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class AuthController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        parent::actions();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['create']
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request->post();

        if (isset($request['email']) && isset($request['password'])) {
            $user = User::findByEmail($request['email']);
            
            if ($user && $user->validatePassword($request['password'])) {
                $token =  $user->getAuthKey();
                $response = Yii::$app->response;
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->statusCode = 200;
                $response->data = ['token' => $token, 'type' => 'bearer'];
            } else {
                $response = Yii::$app->response;
                $response->format = \yii\web\Response::FORMAT_JSON;
                $response->statusCode = 400;
                $response->data = ['error' => 'Login inválido'];
            }
        } else {
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->statusCode = 400;
            $response->data = ['error' => 'campos email e password são obrigatórios'];
        }
    }

}
