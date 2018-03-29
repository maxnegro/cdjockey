<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cdmap`.
 */
class m180328_201745_create_isomap_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('isomap', [
            'id' => $this->primaryKey(),
            'isofile' => $this->text(),
            'sharename' => $this->text(),
            'sharedesc' => $this->text(),
            'lastupdated' => $this->timestamp(),
            'enable' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('isomap');
    }
}
