<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;

class ProductForm extends Model
{
    public $id;
    public $name;
    public $description;
    public $imageFile;
    public $image;
    public $category_id;
    public $price;
    public $quantity_available;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'category_id', 'price', 'quantity_available'], 'safe'],
            [['name', 'description', 'category_id', 'price', 'quantity_available'], 'required'],
            [['description'], 'string'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['image'], 'string', 'skipOnEmpty' => true],
            [['category_id', 'quantity_available'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Save uploaded imageFile
     */
    public function upload($update = false)
    {
        if ($this->validate()) {
            if (!$update) {
                $model = new Product;
            } else {
                $model = $update;
            }
            $model->attributes = \Yii::$app->request->post('ProductForm');
            if(!empty($this->imageFile)) {
                $timestamp = time();
                $image = $this->imageFile->baseName . '_' . $timestamp . '.' . $this->imageFile->extension;
                $imgpath = 'img/' . $this->imageFile->baseName . '_' . $timestamp . '.' . $this->imageFile->extension;
                $thumbnail = 'thumbs/' . $this->imageFile->baseName . '_' . $timestamp . '_thumb' . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($imgpath);
                Image::thumbnail($imgpath, 225, 225)->save($thumbnail, ['quality' => 80]);
                $model->image = $image;
            }
            $model->save();
            return $model->id;
        } else {
            return false;
        }
    }
}
