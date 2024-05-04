<?php

namespace app\modules\api\helpers;
use Yii;

class Response
{
    public static function JSON($statusCode, $message = "", $pages = [])
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->statusCode = $statusCode;
        $hasError = $statusCode < 400 ? false : true;
        $statusMessage = $hasError ? 'error' : 'success';
        $header = $hasError ? 'message' : 'data';
        $response->data = count($pages) > 0 ? ['status' => $statusMessage, $header => $message, 'pagination' => $pages] : ['status' => $statusMessage, $header => $message];
    }
}
