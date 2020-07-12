<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use yii\data\ActiveDataProvider;
use app\models\Cart;

class OrderController extends \yii\web\Controller
{
    public $cart;

    /**
     * Set variables
     */
    public function init()
    {
        parent::init();

        $this->cart = new Cart();
    }

    /**
     * Display index view
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Add product to cart
     */
    public function actionPurchase($id, $count)
    {
        if($this->cart->purchase($id, $count)) {
            Yii::$app->session->setFlash('success', "Product added to your cart");
            return $this->redirect(['product/view', 'id' => $id]);
        } else {
            Yii::$app->session->setFlash('error', "Product out of stock");
            return $this->redirect(['product/view', 'id' => $id]);
        }
    }

    /**
     * Remove one product from cart
     */
    public function actionRemoveItem($id)
    {
        $this->cart->removeItem($id);
        return $this->redirect('index');
    }

    /**
     * Remove all products of specific kind
     */
    public function actionDestroyItem($id)
    {
        $this->cart->destroyItem($id);
        return $this->redirect('index');
    }

    /**
     * Remove all items from cart
     */
    public function actionDestroyCart()
    {
        $this->cart->destroyCart();
        return $this->redirect(['site/index']);
    }

    /**
     * Finalize order
     */
    public function actionCheckOut()
    {
        $this->cart->checkOut();
        $this->cart->destroyCart();
        return $this->redirect('my-orders');
    }

    /**
     * Show user order history
     */
    public function actionMyOrders()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->where(['customer_id' => Yii::$app->user->id]),
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('my-orders', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
