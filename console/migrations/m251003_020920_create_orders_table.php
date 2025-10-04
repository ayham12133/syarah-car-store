<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m251003_020920_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'car_listing_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'purchase_price' => $this->decimal(10, 2)->notNull(),
            'purchase_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'notes' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Add foreign key constraints
        $this->addForeignKey(
            'fk-orders-car_listing_id',
            '{{%orders}}',
            'car_listing_id',
            '{{%car_listing}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-orders-user_id',
            '{{%orders}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Add indexes
        $this->createIndex('idx-orders-car_listing_id', '{{%orders}}', 'car_listing_id');
        $this->createIndex('idx-orders-user_id', '{{%orders}}', 'user_id');
        $this->createIndex('idx-orders-purchase_date', '{{%orders}}', 'purchase_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-orders-car_listing_id', '{{%orders}}');
        $this->dropForeignKey('fk-orders-user_id', '{{%orders}}');
        $this->dropTable('{{%orders}}');
    }
}
