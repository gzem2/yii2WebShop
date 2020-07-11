<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "productcategory".
 *
 * @property int $id
 * @property string|null $category_name
 * @property string|null $category_description
 */
class ProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productcategory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_description'], 'string'],
            [['category_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'category_description' => 'Category Description',
        ];
    }

    /**
     * Get name of category by id
     */
    public static function findNameById($id)
    {
        return static::findOne(['id' => $id])->category_name;
    }

    /**
     * Return array of id => Category names
     */
    public static function getCategoryNames()
    {
        $rows = static::find()->all();
        return ArrayHelper::map($rows, 'id', 'category_name');
    }
}
