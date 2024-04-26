<?php
namespace app\rbac;

use app\models\User;
use yii\rbac\Rule;

class UserRule extends Rule {

    public $name = 'owner-user';

    public function execute($user, $item, $params) {
        return isset($params['user']) ? intval($params['user']) == intval($user) : false;
    }
}