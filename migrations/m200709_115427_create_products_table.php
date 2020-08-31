<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m200709_115427_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT . ' NOT NULL',
            'image' => $this->string()->defaultValue('no_image.jpg'),
            'category_id' => $this->integer()->notNull()->defaultValue(1),
            'price' => Schema::TYPE_FLOAT . ' NOT NULL',
            'quantity_available' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-products-category_id}}',
            '{{%products}}',
            'category_id'
        );

         // add foreign key for table `{{%productcategory}}`
         $this->addForeignKey(
            '{{%fk-products-category_id}}',
            '{{%products}}',
            'category_id',
            '{{%productcategory}}',
            'id',
            'CASCADE'
        );
        
        // Add default products
        for ($i = 0; $i < 5; ++$i) {
            $this->insert('{{%products}}', [
                'id' => $i+1,
                'name' => 'Product №' . ($i+1),
                'description' => 'Product description',
                'category_id' => 1,
                'price' => 5.0,
                'quantity_available' => rand(0, 10)
            ]);
        }

        for ($i = 0; $i < 4; ++$i) {
            $this->insert('{{%products}}', [
                'id' => $i+6,
                'name' => 'Product №' . ($i+6),
                'description' => 'Product description',
                'category_id' => 2,
                'price' => 10.0,
                'quantity_available' => rand(0, 10)
            ]);
        }

        for ($i = 0; $i < 3; ++$i) {
            $this->insert('{{%products}}', [
                'id' => $i+10,
                'name' => 'Product №' . ($i+10),
                'description' => 'Product description',
                'category_id' => 3,
                'price' => 15.0,
                'quantity_available' => rand(0, 10)
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%productcategory}}`
        $this->dropForeignKey(
            '{{%fk-products-category_id}}',
            '{{%products}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-products-category_id}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}
