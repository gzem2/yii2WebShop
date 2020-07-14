<?php
use yii\grid\GridView;

$this->title = 'My Orders';
?>

<h3>My Orders</h3>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id',
            'label' => 'Order id'
        ],
        'total_price',
        'status'
    ],
]); 
?>