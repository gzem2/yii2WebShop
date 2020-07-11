<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orderitem}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%order}}`
 */
class m200709_123742_create_orderitem_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orderitem}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->defaultValue(1),
        ]);

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-orderitem-order_id}}',
            '{{%orderitem}}',
            'order_id'
        );

        // add foreign key for table `{{%order}}`
        $this->addForeignKey(
            '{{%fk-orderitem-order_id}}',
            '{{%orderitem}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-orderitem-product_id}}',
            '{{%orderitem}}',
            'product_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-orderitem-product_id}}',
            '{{%orderitem}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%order}}`
        $this->dropForeignKey(
            '{{%fk-orderitem-order_id}}',
            '{{%orderitem}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-orderitem-order_id}}',
            '{{%orderitem}}'
        );

        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-orderitem-product_id}}',
            '{{%orderitem}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-orderitem-product_id}}',
            '{{%orderitem}}'
        );

        $this->dropTable('{{%orderitem}}');
    }
}
