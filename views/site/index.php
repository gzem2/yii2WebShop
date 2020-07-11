<?php

use app\models\ProductCategory;

/* @var $this yii\web\View */

$this->title = 'WebShop';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-2">
                <h3>Categories</h3>

                <?php foreach (ProductCategory::find()->all() as $c): ?>
                    <p><a class="btn" href="category?name=<?php echo str_replace(' ', '_', $c->category_name) ?>"><?php echo $c->category_name ?></a></p>
                <?php endforeach;?>
            </div>
            <h3><a href="site">Products</a></h3>
            <div class="col-lg-10 product-container">
                <?php foreach ($model as $row): ?>
                    <div class="product">
                    <p><a href="<?php echo \Yii::getAlias('@web')?>/product/view?id=<?php echo $row->id?>"><?php echo $row->name ?></a></p>
                    </div>
                <?php endforeach;?>
            </div>

                <?php ?>

            </div>

        </div>

    </div>
</div>
