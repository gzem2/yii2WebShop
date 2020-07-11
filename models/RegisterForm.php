<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 *
 * @property User|null $customer This property is read-only.
 *
 */
class RegisterForm extends Model
{
    public $email;
    public $password;
    public $address;
    public $rememberMe = true;

    private $_customer = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'address'], 'safe'],
            // fields are required
            [['email', 'password', 'address'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // email has to be unique
            //['email', 'unique'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Logs in a customer using the provided email and password.
     * @return bool whether the customer is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getCustomer(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Create new customer
     * @return bool whether the customer is created and logged
     */
    public function register()
    {
        if ($this->validate()) {
            if($this->getCustomer()) {
                $this->addError('email', 'This email is already in use.');
            } else {
                $customer = new Customer();
                $customer->attributes = \Yii::$app->request->post('RegisterForm');
                $customer->setPassword($this->password);

                $customer->save();

                // Assign RBAC role to customer
                $auth = \Yii::$app->authManager;
                $customerRole = $auth->getRole('customer');
                $auth->assign($customerRole, $customer->getId());

                return Yii::$app->user->login($customer, $this->rememberMe ? 3600*24*30 : 0);
            }
        }
        return false;
    }

    /**
     * Finds customer by [[email]]
     *
     * @return Customer|null
     */
    public function getCustomer()
    {
        if ($this->_customer === false) {
            $this->_customer = Customer::findByEmail($this->email);
        }

        return $this->_customer;
    }
}
