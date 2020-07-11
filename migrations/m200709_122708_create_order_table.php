<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%category}}`
 */
class m200709_122708_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'total_price' => $this->integer(),
            'status' => $this->string(),
        ]);

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-order-customer_id}}',
            '{{%order}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-order-customer_id}}',
            '{{%order}}',
            'customer_id',
            '{{%customer}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%customer}}`
        $this->dropForeignKey(
            '{{%fk-order-customer_id}}',
            '{{%order}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-order-customer_id}}',
            '{{%order}}'
        );

        $this->dropTable('{{%order}}');
    }
}
