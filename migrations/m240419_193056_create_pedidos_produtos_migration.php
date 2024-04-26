<?php

use yii\db\Migration;

/**
 * Class m240419_193056_create_pedidos_produtos_migration
 */
class m240419_193056_create_pedidos_produtos_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pedidos_produtos', [
            'id' => $this->primaryKey(),
            'pedido_id' => $this->integer()->notNull(),
            'produto_id' => $this->integer()->notNull(),
            'preco' => $this->double(8)->notNull(),
            'qtd'   => $this->integer()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pedidos_produtos');
    }


}
