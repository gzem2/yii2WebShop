<?php

use app\models\ProductCategory;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

$this->title = 'WebShop';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-2">
                <h3><a href="<?php echo \Yii::getAlias('@web') ?>/">Categories</a></h3>

                <?php foreach (ProductCategory::find()->all() as $c) : ?>
                    <p><a class="btn" href="category?name=<?php echo str_replace(' ', '_', $c->category_name) ?>"><?php echo $c->category_name ?></a></p>
                <?php endforeach; ?>
            </div>
            <h3><a href="<?php echo \Yii::getAlias('@web') ?>/">Products</a></h3>
            <div class="col-lg-10 product-container">
                <?php foreach ($models as $row) : ?>
                    <div class="product">
                        <a href="<?php echo \Yii::getAlias('@web') ?>/product/view?id=<?php echo $row->id ?>">
                            <img src="<?php echo Url::to(['/thumbs/']) . '/' . $row->getThumbnail(); ?>">
                            <p class="product-title"><?php echo $row->name ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pager">
                <?php echo LinkPager::widget([
                    'pagination' => $pages,
                ]); ?>
            </div>
        </div>

    </div>

</div>