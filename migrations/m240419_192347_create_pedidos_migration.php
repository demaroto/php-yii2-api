<?php

use yii\db\Migration;

/**
 * Class m240419_192347_create_pedidos_migration
 */
class m240419_192347_create_pedidos_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pedidos', [
            'id' => $this->primaryKey(),
            'cliente_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('pedidos');
    }

   
}
