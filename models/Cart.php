<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Product;
use app\models\Order;
use app\models\OrderItem;

/**
 * Manage cart data
 */
class Cart extends Model
{
    public $session;

    /**
     * Set session variables
     */
    public function init()
    {
        parent::init();

        $this->session = Yii::$app->session;

        if (!$this->session->isActive) {
            $this->session->open();
        }

        if (!isset($this->session['orderitems'])) {
            $this->session['orderitems'] = new \ArrayObject;;
        }

        if (!isset($this->session['totalprice'])) {
            $this->session['totalprice'] = 0;
        }
    }

    /**
     * Add product to cart
     */
    public function purchase($id, $count)
    {
        $product = Product::findOne(['id' => $id]);
        $price = $product->price;

        // Check if item is out of stock
        if (!isset($this->session['orderitems'][strval($id)])) {
            if (($product->quantity_available - $count) < 0) {
                return false;
            }
        } else {
            if (($product->quantity_available - ($count + $this->session['orderitems'][strval($id)])) < 0) {
                return false;
            }
        }

        // Add item to cart
        if (!isset($this->session['orderitems'][strval($id)])) {
            $this->session['orderitems'][strval($id)] = $count;
            $this->session['totalprice'] += $price * $count;
        } else {
            $this->session['orderitems'][strval($id)] += $count;
            $this->session['totalprice'] += $price * $count;
        }
        return true;
    }

    /**
     * Remove one product from cart
     */
    public function removeItem($id)
    {
        if (isset($this->session['orderitems'][strval($id)])) {
            $price = Product::findOne(['id' => $id])->price;
            $count = isset($this->session['orderitems'][strval($id)]);
            if ($count > 1) {
                $this->session['orderitems'][strval($id)] = $count - 1;
            } else {
                unset($this->session['orderitems'][strval($id)]);
            }
            $this->session['totalprice'] -= $price;
        }
    }

    /**
     * Remove all products of specific kind
     */
    public function destroyItem($id)
    {
        if (isset($this->session['orderitems'][strval($id)])) {
            $price = Product::findOne(['id' => $id])->price;
            $this->session['totalprice'] -= $price;
            unset($this->session['orderitems'][strval($id)]);
        }
    }

    /**
     * Remove all items from cart
     */
    public function destroyCart()
    {
        $this->session->destroy();
    }

    /**
     * Finalize order
     */
    public function checkOut()
    {
        if (!empty($this->session['orderitems'])) {
            // Create new order in db
            $order = new Order();
            $order->customer_id = Yii::$app->user->id;
            $order->total_price = $this->session['totalprice'];
            $order->status = 'submitted';
            $order->save();

            foreach ($this->session['orderitems'] as $id => $count) {
                // Create orderitems in db
                $orderitem = new OrderItem();
                $orderitem->order_id = $order->id;
                $orderitem->product_id = $id;
                $orderitem->quantity = $count;
                $orderitem->save();

                // Decrement available product quantity
                $item = Product::findOne(['id' => $id]);
                $item->quantity_available -= $count;
                $item->save();
            }
        }
    }
}
