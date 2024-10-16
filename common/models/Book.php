<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%book}}".
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $title
 * @property string $authors
 * @property string $publisher
 * @property int $release
 * @property string|null $isbn
 * @property int $pages
 * @property string|null $description
 * @property int $created_at
 * @property int $updated_at
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'type_id', 'release', 'pages', 'created_at', 'updated_at'], 'integer'],
            [['title', 'authors', 'publisher', 'release', 'pages', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['authors'], 'string', 'max' => 100],
            [['publisher'], 'string', 'max' => 50],
            [['isbn'], 'string', 'max' => 64],
            ['release', 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Release year must be a 4-digit number.'],
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
            'category_id' => Yii::t('app', 'Category ID'),
            'title' => Yii::t('app', 'Title'),
            'authors' => Yii::t('app', 'Authors'),
            'publisher' => Yii::t('app', 'Publisher'),
            'release' => Yii::t('app', 'Release'),
            'isbn' => Yii::t('app', 'Isbn'),
            'pages' => Yii::t('app', 'Pages'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookQuery(get_called_class());
    }
}
