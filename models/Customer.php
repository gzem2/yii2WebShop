<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class Customer extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'address', 'authKey'], 'safe'],
            // fields are required
            [['email', 'password', 'address'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // email has to be unique
            ['email', 'unique'],
        ];
    }

    /**
     * Generate auth_key in insert row before saving
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds customer by email
     */
    public static function findByEmail($email){
        return static::findOne(['email' => $email]);
    }

    
    /**
     * Finds customer by email
     */
    public static function findByUsername($email)
    {
        return findByEmail($email);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword($password) {
        $this->password = \Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current customer
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
}
