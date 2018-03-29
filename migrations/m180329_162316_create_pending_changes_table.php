<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pending_changes`.
 */
class m180329_162316_create_pending_changes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pending_changes', [
            'id' => $this->primaryKey(),
            'createdAt' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pending_changes');
    }
}
