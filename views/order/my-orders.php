<?php
use yii\grid\GridView;
?>

<h3>My Orders</h3>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
]); 
?>