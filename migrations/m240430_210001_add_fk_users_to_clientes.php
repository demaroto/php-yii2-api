<?php

use yii\db\Migration;

/**
 * Class m240430_210001_add_fk_users_to_clientes
 */
class m240430_210001_add_fk_users_to_clientes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk_users_cliente_id', 'clientes', 'user_id', 'usuarios', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_users_cliente_id', 'clientes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240430_210001_add_fk_users_to_clientes cannot be reverted.\n";

        return false;
    }
    */
}
