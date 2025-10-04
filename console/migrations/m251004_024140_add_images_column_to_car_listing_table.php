<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%car_listing}}`.
 */
class m251004_024140_add_images_column_to_car_listing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%car_listing}}', 'images', $this->text()->null()->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%car_listing}}', 'images');
    }
}
