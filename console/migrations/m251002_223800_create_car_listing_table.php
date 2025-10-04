<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%car_listing}}`.
 */
class m251002_223800_create_car_listing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%car_listing}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'make' => $this->string(100)->notNull(),
            'model' => $this->string(100)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'mileage' => $this->integer()->notNull(),
            'description' => $this->text(),
            'status' => $this->string(20)->notNull()->defaultValue('available'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Add indexes for better performance
        $this->createIndex('idx-car_listing-make', '{{%car_listing}}', 'make');
        $this->createIndex('idx-car_listing-model', '{{%car_listing}}', 'model');
        $this->createIndex('idx-car_listing-year', '{{%car_listing}}', 'year');
        $this->createIndex('idx-car_listing-status', '{{%car_listing}}', 'status');
        $this->createIndex('idx-car_listing-price', '{{%car_listing}}', 'price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%car_listing}}');
    }
}
