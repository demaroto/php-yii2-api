<?php

use yii\db\Migration;

/**
 * Class m240419_190910_create_clientes_migration
 */
class m240419_190910_create_clientes_migration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('clientes', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'cpf' => $this->string()->notNull(),
            'cep' => $this->integer(),
            'logradouro' => $this->string()->notNull(),
            'numero' => $this->integer()->notNull(),
            'complemento' => $this->string(),
            'cidade' => $this->string()->notNull(),
            'estado' => $this->char(2),
            'foto' => $this->string(),
            'sexo' => $this->char(1),
            'create_at' => $this->dateTime(),
            'update_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('clientes');
    }


}
