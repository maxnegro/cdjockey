<?php

use yii\db\Migration;

/**
 * Handles adding lastupdated to table `isomap`.
 */
class m180404_130811_add_lastupdated_column_to_isomap_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('isomap', 'lastupdated', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('isomap', 'lastupdated');
    }
}
