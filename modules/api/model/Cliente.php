<?php

namespace app\modules\api\model;

use app\models\User;
use Yii;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id
 * @property string $nome
 * @property string $cpf
 * @property int|null $cep
 * @property string $logradouro
 * @property int $numero
 * @property string|null $complemento
 * @property string $cidade
 * @property string|null $estado
 * @property string|null $foto
 * @property string|null $sexo
 * @property string|null $create_at
 * @property string|null $update_at
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'nome', 'cpf', 'logradouro', 'numero', 'cidade', 'estado', 'sexo'], 'required'],
            [['cpf', 'user_id'], 'unique'],
            [['cep', 'numero'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['nome'], 'string', 'max' => 100],
            [['logradouro', 'complemento', 'cidade', 'foto'], 'string', 'max' => 255],
            [['estado'], 'string', 'max' => 2],
            [['sexo'], 'string', 'max' => 1],
            [['cpf'], 'cpfValidate'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'cep' => 'Cep',
            'logradouro' => 'Logradouro',
            'numero' => 'Numero',
            'complemento' => 'Complemento',
            'cidade' => 'Cidade',
            'estado' => 'Estado',
            'foto' => 'Foto',
            'sexo' => 'Sexo',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'user_id' => 'ID do Usuário'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProdutos()
    {
        return $this->hasMany(Produto::class, ['cliente_id' => 'id']);
    }

    public function cpfValidate($attr, $params)
    {
        $cpf = preg_replace('/[^0-9]/is', '', $this->cpf);
        //Inválido
        if (strlen($cpf) != 11 || (preg_match('/(\d)\1{10}/', $cpf)))
            $this->addError($attr, 'Seu CPF está inválido');

        //Calcular CPF 
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $this->addError($attr, 'CPF com números incorretos.');
            }
        }
    }
}
