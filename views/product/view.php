<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
if (\Yii::$app->user->can('manageProduct')) {
    $this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['/']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\Yii::$app->user->can('manageProduct')) : ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>
    <?php if (!\Yii::$app->user->can('manageProduct')) : ?>
        <p>
            <?= Html::a('Add to Cart', ['order/purchase', 'id' => $model->id, 'count' => 1], ['class' => 'btn btn-warning']) ?>
            <?php Modal::begin([
                'header' => '<h4>Buy ' . $model->name . ' in bulk!</h4>',
                'toggleButton' => ['label' => 'Bulk Order', 'class' => 'btn btn-warning'],
            ]);
            echo 'Enter amount:<br><input type="text" id="bulkcount" name="bulkcount" value="1"><br><br>';
            echo Html::a('Purchase', ['order/purchase', 'id' => $model->id], [
                'id' => 'modalbtn', 
                'class' => 'btn btn-warning modify', 
                'onClick' => 'function sethref(){
                        $("#modalbtn").attr("href", $("#modalbtn").attr("href") + "&count=" + $("#bulkcount").val());
                };sethref()'
            ]);
            Modal::end(); ?>
        </p>
    <?php endif; ?>
    <?php
    $attributes = [
        'name',
        'description:ntext',
        [
            'label' => 'Category',
            'attribute' => 'category_id',
        ],
        'price',
        'quantity_available',
    ];
    if (\Yii::$app->user->can('manageProduct')) {
        array_unshift($attributes, 'id');
    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>
    <img src="<?php echo Url::to(['/img/']) . '/' . $model->image; ?>">

</div>