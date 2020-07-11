<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderitem".
 *
 * @property int $id
 * @property int $order_id
 * @property int|null $quantity
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orderitem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'quantity'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'quantity' => 'Quantity',
        ];
    }
}
