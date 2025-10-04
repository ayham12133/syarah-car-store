<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%exports}}`.
 */
class m251003_035734_create_exports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%exports}}', [
            'id' => $this->primaryKey(),
            'filename' => $this->string(255)->notNull(),
            'file_path' => $this->string(500)->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'), // pending, processing, completed, failed
            'filters' => $this->text(), // JSON string of applied filters
            'total_records' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'completed_at' => $this->integer(),
            'error_message' => $this->text(),
        ]);

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-exports-created_by',
            '{{%exports}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Add indexes
        $this->createIndex('idx-exports-status', '{{%exports}}', 'status');
        $this->createIndex('idx-exports-created_by', '{{%exports}}', 'created_by');
        $this->createIndex('idx-exports-created_at', '{{%exports}}', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-exports-created_by', '{{%exports}}');
        $this->dropTable('{{%exports}}');
    }
}
