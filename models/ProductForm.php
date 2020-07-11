<?php

namespace app\models;

use Yii;
use yii\base\Model;

use yii\web\UploadedFile;

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
                $this->imageFile->saveAs('img/' . $this->imageFile->baseName . '.' . $this->imageFile->extension); 
                $model->image = $this->imageFile->baseName . '.' . $this->imageFile->extension;
            }
            $model->save();
            return $model->id;
        } else {
            return false;
        }
    }
}
