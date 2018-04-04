<?php

use yii\db\Migration;

/**
 * Handles dropping lastupdated from table `isomap`.
 */
class m180404_130730_drop_lastupdated_column_from_isomap_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('isomap', 'lastupdated');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('isomap', 'lastupdated', $this->timestamp());
    }
}
