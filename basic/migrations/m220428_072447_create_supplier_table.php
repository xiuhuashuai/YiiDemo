<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplier}}`.
 */
class m220428_072447_create_supplier_table extends Migration
{
    public $tableName = 'supplier';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->unsigned()->notNull()->comment('Supplier Name'),
            'code' => $this->char(3)->unsigned()->defaultValue(null)->comment('Supplier Code')
        ]);
        $this->addColumn($this->tableName, 't_status', "enum('ok','hold') COLLATE utf8_unicode_ci DEFAULT 'ok' COMMENT 'Supplier Status'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
