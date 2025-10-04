<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "exports".
 *
 * @property int $id
 * @property string $filename
 * @property string $file_path
 * @property string $status
 * @property string|null $filters
 * @property int $total_records
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $completed_at
 * @property string|null $error_message
 *
 * @property User $createdBy
 */
class Export extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    
    public static function tableName()
    {
        return '{{%exports}}';
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    
    public function rules()
    {
        return [
            [['filename', 'file_path', 'created_by'], 'required'],
            [['status'], 'string'],
            [['total_records', 'created_by', 'created_at', 'updated_at', 'completed_at'], 'integer'],
            [['filters', 'error_message'], 'string'],
            [['filename'], 'string', 'max' => 255],
            [['file_path'], 'string', 'max' => 500],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_PROCESSING, self::STATUS_COMPLETED, self::STATUS_FAILED]],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'file_path' => 'File Path',
            'status' => 'Status',
            'filters' => 'Filters',
            'total_records' => 'Total Records',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'completed_at' => 'Completed At',
            'error_message' => 'Error Message',
        ];
    }

    
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }
    
    public function getStatusBadgeClass()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning',
            self::STATUS_PROCESSING => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }
    
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    
    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }
    
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    public function getFormattedFileSize()
    {
        if (!$this->isCompleted() || !file_exists($this->file_path)) {
            return 'N/A';
        }

        $bytes = filesize($this->file_path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    public function getFormattedCompletionDate()
    {
        if (!$this->completed_at) {
            return 'N/A';
        }

        return date('M j, Y H:i:s', $this->completed_at);
    }
    
    public function getFormattedCreationDate()
    {
        return date('M j, Y H:i:s', $this->created_at);
    }

    
    public function getFiltersArray()
    {
        if (empty($this->filters)) {
            return [];
        }

        return json_decode($this->filters, true) ?: [];
    }
    
    public function setFiltersArray($filters)
    {
        $this->filters = json_encode($filters);
    }
    
    public function markCompleted($totalRecords)
    {
        $this->status = self::STATUS_COMPLETED;
        $this->total_records = $totalRecords;
        $this->completed_at = time();
        $this->error_message = null;
        $this->save(false);
    }
    
    public function markFailed($errorMessage)
    {
        $this->status = self::STATUS_FAILED;
        $this->error_message = $errorMessage;
        $this->completed_at = time();
        $this->save(false);
    }
    
    public function markProcessing()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->save(false);
    }
    
    public static function findByUserId($userId)
    {
        return static::find()->where(['created_by' => $userId])->orderBy(['created_at' => SORT_DESC]);
    }
    
    public static function findByStatus($status)
    {
        return static::find()->where(['status' => $status])->orderBy(['created_at' => SORT_DESC]);
    }
}
