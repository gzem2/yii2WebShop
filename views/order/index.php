<?php

use app\models\Product;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'My Cart';

$totalprice = Yii::$app->session->get('totalprice');
$orderitems = Yii::$app->session->get('orderitems');
$data = [];
foreach ($orderitems as $id => $count) {
    $product = Product::findOne(['id' => $id]);
    $data[] = ['id' => $id, 'name' => $product->name, 'amount' => $count, 'price' => $product->price];
}
$dataProvider = new ArrayDataProvider([
    'allModels' => $data,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
?>
<h3>My Cart</h3>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        [
            'label' => 'Item Price',
            'attribute' => 'price',
        ],
        'amount',
        [
            'label' => 'Item Total Cost',
            'attribute' => 'price',
            'content' => function($model, $key, $index, $column) {
                return $model['price'] * $model['amount'];
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{myButton}',
            'buttons' => [
                'myButton' => function ($url, $model, $key) {
                    return Html::a('X Remove', ['remove-item', 'id' => $model['id']], [
                        'class' => 'btn btn-primary btn-xs',
                    ]);
                },
            ]
        ]
    ]
]);

if ($orderitems->count() != 0) {
    echo '<p><h4>';
    echo 'Order Total Cost: ';
    echo $totalprice;
    echo '</h4></p>';
    echo '<p>';
    echo Html::a('Check out', ['check-out'], [
        'class' => 'btn btn-warning',
        'data' => [
            'confirm' => 'Proceed with check out?',
            'method' => 'post',
        ],
    ]);
    echo " ";
    echo Html::a('Clear cart', ['destroy-cart'], [
        'class' => 'btn btn-primary',
        'data' => [
            'confirm' => 'All items will be lost, proceed?',
            'method' => 'post',
        ],
    ]);
    echo '</p>';
}
?>