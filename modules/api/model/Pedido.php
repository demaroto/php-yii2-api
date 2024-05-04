<?php

namespace app\modules\api\model;

use Yii;

/**
 * This is the model class for table "pedidos".
 *
 * @property int $id
 * @property int $cliente_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Clientes $cliente
 * @property PedidosProdutos[] $pedidosProdutos
 */
class Pedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedidos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id'], 'required'],
            [['cliente_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['cliente_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::class, ['id' => 'cliente_id']);
    }

    /**
     * Gets query for [[PedidosProdutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidosProdutos()
    {
        return $this->hasMany(PedidosProdutos::class, ['pedido_id' => 'id']);
    }
}
