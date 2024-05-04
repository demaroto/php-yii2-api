<?php

use yii\db\Migration;

/**
 * Class m240419_193843_create_users_migration
 */
class m240419_193843_create_users_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('usuarios', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull(),
            'password' => $this->string()->notNull(),
            'token' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'expire_at' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11)
        ]);

        $this->insert('usuarios', [
            'nome' => 'Administrador',
            'email' => 'admin@email.com.br',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'updated_at' => strtotime('now'),
            'created_at' => strtotime('now')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('usuarios');
    
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240419_193843_create_users_migration cannot be reverted.\n";

        return false;
    }
    */
}
