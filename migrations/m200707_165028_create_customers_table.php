<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%customers}}`.
 */
class m200707_165028_create_customers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL',
            'address' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_token' => $this->string()->notNull()->defaultValue(''),
            'enable_autologin' => $this->boolean()->notNull()->defaultValue(True),
        ]);

        // Create admin user
        $this->insert('{{%customers}}', [
            'email' => 'admin@admin.com',
            'password' => \Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'address' => 'admin',
            'auth_key' => \Yii::$app->security->generateRandomString(),
        ]);

        // Create regular user
        $this->insert('{{%customers}}', [
            'email' => 'user@user.com',
            'password' => \Yii::$app->getSecurity()->generatePasswordHash('user'),
            'address' => 'user',
            'auth_key' => \Yii::$app->security->generateRandomString(),
        ]);

        // Assign RBAC roles to default users
        /*
        $auth = \Yii::$app->authManager;

        $admin = Customer::find()->where(['email' => 'admin@admin.com'])->one();
        $adminRole = $auth->getRole('admin');
        $auth->assign($adminRole, $admin->getId());
        
        $user = Customer::find()->where(['email' => 'user@user.com'])->one();
        $customerRole = $auth->getRole('customer');
        $auth->assign($customerRole, $user->getId());
        */
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customers}}');
    }
}
