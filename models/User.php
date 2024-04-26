<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    private $tokenExpiration = 60 * 24 * 1; //Um dia
    public static function tableName()
    {
        return 'usuarios';
    }

    public function fields()
    {
        return [
            'id',
            'nome',
            'email',
            'token',
        ];
    }

    public function rules()
    {
        return [
            [['nome', 'email', 'password'], 'required'],
            ['token', 'string', 'max' => 32],
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            ['email', 'string', 'max' => 100],
            ['email', 'unique']
        ];
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            //Caso seja novo cadastro
            if ($this->isNewRecord) {
                $this->token = \Yii::$app->security->generateRandomString();
                $this->expire_at = strtotime('now') + $this->tokenExpiration;
            }else {
                if ($this->password !== $this->oldAttributes['password']) {
                    $this->setPassword($this->password);
                }
            }

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public static function loginByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }


    public function generateAuthKey()
    {
        $this->token = Yii::$app->security->generateRandomString();
        $this->expire_at = $this->tokenExpiration + strtotime('now');
        $this->save();
        return $this->token;
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->validateAuthKey($this->token) ? $this->token : $this->generateAuthKey();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($token)
    {
        return $this->token === $token && $token != null && $this->expire_at >= strtotime('now');
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->oldAttributes['password']);
    }

    public function setPassword($password) {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }
}
