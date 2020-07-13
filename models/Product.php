<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|null $category_id
 * @property float $price
 * @property int $quantity_available
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'price', 'quantity_available'], 'safe'],
            [['name', 'description', 'category_id', 'price', 'quantity_available'], 'required'],
            [['description'], 'string'],
            [['image'], 'string', 'skipOnEmpty' => true],
            [['category_id', 'quantity_available'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Product Name',
            'description' => 'Description',
            'image' => 'Product Image',
            'category_id' => 'Category',
            'price' => 'Price',
            'quantity_available' => 'Quantity Available',
        ];
    }

    /**
     * Return products from specific category
     */
    public static function findByCategory($category_id)
    {
        return static::find()->where(['category_id' => $category_id])->all();
    }

    /**
     * Return name of thumbnail
     */
    public function getThumbnail()
    {
        $img = explode('.', $this->image);
        $ext = array_pop($img);
        $img[0] = $img[0] . '_thumb';
        array_push($img, $ext);
        return implode('.', $img);
    }
}
