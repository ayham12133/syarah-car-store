<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $car_listing_id
 * @property int $user_id
 * @property float $purchase_price
 * @property string $purchase_date
 * @property string|null $notes
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CarListing $carListing
 * @property User $user
 */
class Order extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    
    public function rules()
    {
        return [
            [['car_listing_id', 'user_id', 'purchase_price'], 'required'],
            [['car_listing_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['purchase_price'], 'number'],
            [['purchase_date'], 'safe'],
            [['notes'], 'string'],
            [['car_listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarListing::class, 'targetAttribute' => ['car_listing_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_listing_id' => 'Car Listing ID',
            'user_id' => 'User ID',
            'purchase_price' => 'Purchase Price',
            'purchase_date' => 'Purchase Date',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getCarListing()
    {
        return $this->hasOne(CarListing::class, ['id' => 'car_listing_id']);
    }

    
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    
    public function getFormattedPrice()
    {
        return '$' . number_format($this->purchase_price, 2);
    }
    
    public function getFormattedPurchaseDate()
    {
        return date('Y-m-d', strtotime($this->purchase_date));
    }
    
    public static function findByUserId($userId)
    {
        return static::find()->where(['user_id' => $userId])->orderBy(['purchase_date' => SORT_DESC]);
    }
}
