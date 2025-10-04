<?php

use yii\db\Migration;

class m251002_192558_add_admin_role_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_admin', $this->boolean()->defaultValue(false)->comment('Admin user flag'));
        
        // Add index for faster queries
        $this->createIndex('idx-user-is_admin', '{{%user}}', 'is_admin');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-is_admin', '{{%user}}');
        $this->dropColumn('{{%user}}', 'is_admin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251002_192558_add_admin_role_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
