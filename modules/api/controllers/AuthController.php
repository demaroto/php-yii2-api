<?php

namespace app\modules\api\controllers;

use app\models\User;
use app\modules\api\helpers\Response;

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
                $result = ['token' => $token, 'type' => 'bearer'];
                Response::JSON(200, $result);
            } else {

                $result = ['error' => 'Login inválido'];
                Response::JSON(400, $result);
            }
        } else {

            $result = ['error' => 'campos email e password são obrigatórios'];
            Response::JSON(400, $result);
            
        }
    }

}
