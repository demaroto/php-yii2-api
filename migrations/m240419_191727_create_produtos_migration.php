<?php

use yii\db\Migration;

/**
 * Class m240419_191727_create_produtos_migration
 */
class m240419_191727_create_produtos_migration extends Migration
{
    /**
     * Criando a tabela de Produtos
     */
    public function safeUp()
    {
        $this->createTable('produtos', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'preco' => $this->double(8)->notNull(),
            'foto' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);
    }

    /**
     * Excluindo a tabela produtos
     */
    public function safeDown()
    {
        $this->dropTable('produtos');
    }

}
