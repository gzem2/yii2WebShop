<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%productcategory}}`.
 */
class m200709_122913_create_productcategory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%productcategory}}', [
            'id' => $this->primaryKey(),
            'category_name' => $this->string(),
            'category_description' => $this->text(),
        ]);

        // Add default categories
        $this->insert('{{%productcategory}}', [
            'id' => 1,
            'category_name' => 'Products A',
            'category_description' => 'Description',
        ]);

        $this->insert('{{%productcategory}}', [
            'id' => 2,
            'category_name' => 'Products B',
            'category_description' => 'Description',
        ]);

        $this->insert('{{%productcategory}}', [
            'id' => 3,
            'category_name' => 'Products C',
            'category_description' => 'Description',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%productcategory}}');
    }
}
