<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%storage}}".
 *
 * @property int $id
 * @property int $model_id
 * @property string $file_path
 * @property int $created_at
 * @property int $updated_at
 */
class Storage extends \yii\db\ActiveRecord
{
    public $cover;
    public $book;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%storage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model_id', 'file_path', 'created_at', 'updated_at'], 'required'],
            [['model_id', 'created_at', 'updated_at'], 'integer'],
            [['file_path'], 'string', 'max' => 255],

            [['cover'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'png'], 'wrongExtension' => 'Only image files are allowed for cover.'],
            [['book'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'wrongExtension' => 'Only pdf files are allowed for book.'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => false,
                'updatedByAttribute' => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_id' => Yii::t('app', 'Model ID'),
            'file_path' => Yii::t('app', 'File Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\StorageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\StorageQuery(get_called_class());
    }
}
