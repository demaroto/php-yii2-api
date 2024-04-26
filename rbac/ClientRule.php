<?php
namespace app\rbac;

use yii\rbac\Rule;

class ClientRule extends Rule {

    public $name = 'owner-client';

    public function execute($user, $item, $params) {
        return isset($params['client']) ? $params['client']->user_id == $user : false;
    }
}