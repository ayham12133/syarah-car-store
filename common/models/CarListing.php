<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%car_listing}}".
 *
 * @property int $id
 * @property string $title
 * @property string $make
 * @property string $model
 * @property int $year
 * @property float $price
 * @property int $mileage
 * @property string|null $description
 * @property string $status
 * @property string|null $images JSON array of uploaded image filenames
 * @property int $created_at
 * @property int $updated_at
 */
class CarListing extends ActiveRecord
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_SOLD = 'sold';

    public $imageFiles;

    public static function tableName()
    {
        return '{{%car_listing}}';
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'make', 'model', 'year', 'price', 'mileage'], 'required'],
            [['year', 'mileage', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
            [['description', 'images'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['make', 'model'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_AVAILABLE, self::STATUS_SOLD]],
            [['year'], 'integer', 'min' => 1900, 'max' => date('Y') + 1],
            [['mileage'], 'integer', 'min' => 0],
            [['price'], 'number', 'min' => 0],
            [['imageFiles'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'make' => 'Make',
            'model' => 'Model',
            'year' => 'Year',
            'price' => 'Price',
            'mileage' => 'Mileage',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get available status options
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_SOLD => 'Sold',
        ];
    }

    /**
     * Get status label
     * @return string
     */
    public function getStatusLabel()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Check if car is available
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Check if car is sold
     * @return bool
     */
    public function isSold()
    {
        return $this->status === self::STATUS_SOLD;
    }

    /**
     * Get full car name (make + model + year)
     * @return string
     */
    public function getFullCarName()
    {
        return "{$this->year} {$this->make} {$this->model}";
    }

    /**
     * Get formatted price
     * @return string
     */
    public function getFormattedPrice()
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Get formatted mileage
     * @return string
     */
    public function getFormattedMileage()
    {
        return number_format($this->mileage) . ' miles';
    }

    /**
     * Find available cars
     * @return \yii\db\ActiveQuery
     */
    public static function findAvailable()
    {
        return static::find()->where(['status' => self::STATUS_AVAILABLE]);
    }

    /**
     * Find cars by make
     * @param string $make
     * @return \yii\db\ActiveQuery
     */
    public static function findByMake($make)
    {
        return static::find()->where(['make' => $make]);
    }

    /**
     * Find cars by year range
     * @param int $minYear
     * @param int $maxYear
     * @return \yii\db\ActiveQuery
     */
    public static function findByYearRange($minYear, $maxYear)
    {
        return static::find()->where(['between', 'year', $minYear, $maxYear]);
    }

    /**
     * Find cars by price range
     * @param float $minPrice
     * @param float $maxPrice
     * @return \yii\db\ActiveQuery
     */
    public static function findByPriceRange($minPrice, $maxPrice)
    {
        return static::find()->where(['between', 'price', $minPrice, $maxPrice]);
    }

    public function uploadImages()
    {
        if ($this->imageFiles && is_array($this->imageFiles) && !empty($this->imageFiles)) {
            $paths = $this->getImagesArray(); 
            foreach ($this->imageFiles as $file) {
                if (count($paths) >= 3) break; 
                $fileName = uniqid() . '.' . $file->extension;
                $filePath = \Yii::getAlias('@uploads/cars/') . $fileName;
                
                if ($file->saveAs($filePath)) {
                    $paths[] = $fileName;
                } else {
                    \Yii::error('Failed to upload image: ' . $fileName, 'image-upload');
                }
            }
            $this->images = json_encode($paths);
            return true;
        }
        return false;
    }

    public function getImagesArray()
    {
        return $this->images ? json_decode($this->images) : [];
    }

    public function getImageUrls()
    {
        $images = json_decode($this->images, true);
        $urls = [];

        if ($images) {
            foreach ($images as $image) {
                $urls[] = Yii::getAlias('@uploadsUrl/cars/' . $image);
            }
        }

        return $urls;
    }

    /**
     * Delete a specific image by filename
     * @param string $filename
     * @return bool
     */
    public function deleteImage($filename)
    {
        $images = $this->getImagesArray();
        $key = array_search($filename, $images);
        
        if ($key !== false) {
            // Remove from array
            unset($images[$key]);
            $images = array_values($images); // Re-index array
            
            
            $filePath = \Yii::getAlias('@uploads/cars/') . $filename;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            if(empty($images)){
                $this->images = null;
            }else{
                $this->images = json_encode($images);
            }
            return $this->save(false);
        }
        
        return false;
    }

    /**
     * Get image filename by index
     * @param int $index
     * @return string|null
     */
    public function getImageByIndex($index)
    {
        $images = $this->getImagesArray();
        return isset($images[$index]) ? $images[$index] : null;
    }

    /**
     * Handle image uploads with replacement logic
     * @return bool
     */
    public function uploadImagesWithReplacement()
    {
        if ($this->imageFiles && is_array($this->imageFiles) && !empty($this->imageFiles)) {
            $paths = $this->getImagesArray();
            
            foreach ($this->imageFiles as $index => $file) {
                if ($index >= 3) break; // Max 3 images
                
                $fileName = uniqid() . '.' . $file->extension;
                $filePath = \Yii::getAlias('@uploads/cars/') . $fileName;
                
                if ($file->saveAs($filePath)) {
                    // If we're replacing an existing image at this index
                    if (isset($paths[$index])) {
                        // Delete old file
                        $oldFilePath = \Yii::getAlias('@uploads/cars/') . $paths[$index];
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                    
                    $paths[$index] = $fileName;
                } else {
                    \Yii::error('Failed to upload image: ' . $fileName, 'image-upload');
                }
            }
            
            if(empty($paths)){
                $this->images = null;
            }else{
                $this->images = json_encode($paths);
            }
            return true;
        }
        return false;
    }
}
