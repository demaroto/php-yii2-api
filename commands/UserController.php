<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\httpclient\Client;

class UserController extends Controller
{
    public function actionCreate($nome, $email, $password)
    {
        $client = new Client();
        $requests = [
            'users' => $client->post('http://nginx/api/users', ['nome' => $nome, 'email' => $email, 'password' => $password])
        ];
        
 
            $responses = $client->batchSend($requests);
            $data = $responses['users']->getData();
            if ($responses['users']->isOk) {
            
            
                $response = $this->ansiFormat(json_encode($data['data']), Console::FG_YELLOW);
                echo "Ok: $response";
                return ExitCode::OK;
               
            }
        
            echo $data['message'] . "\n";
            return ExitCode::DATAERR;
            

    }
        
}