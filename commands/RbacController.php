<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        // add "purchaseProduct" permission
        $purchaseProduct = $auth->createPermission('purchaseProduct');
        $purchaseProduct->description = 'Purchase product';
        $auth->add($purchaseProduct);

        // add "manageProduct" permission
        $manageProduct = $auth->createPermission('manageProduct');
        $manageProduct->description = 'Manage product';
        $auth->add($manageProduct);

        // add "manageProductCategory" permission
        $manageProductCategory = $auth->createPermission('manageProductCategory');
        $manageProductCategory->description = 'Manage product category';
        $auth->add($manageProductCategory);

        // add "customer" role and give this role the "purchaseProduct" permission
        $customer = $auth->createRole('customer');
        $auth->add($customer);
        $auth->addChild($customer, $purchaseProduct);

        // add "admin" role and give this role the "manageProduct" permission
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageProduct);
        $auth->addChild($admin, $manageProductCategory);
        //$auth->addChild($admin, $customer);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        $auth->assign($customer, 2);
        $auth->assign($admin, 1);
    }
}