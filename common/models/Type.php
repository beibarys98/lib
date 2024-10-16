<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%type}}".
 *
 * @property int $id
 * @property string $title_kz
 * @property string $title_ru
 */
class Type extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title_kz', 'title_ru'], 'required'],
            [['title_kz', 'title_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title_kz' => Yii::t('app', 'Title Kz'),
            'title_ru' => Yii::t('app', 'Title Ru'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TypeQuery(get_called_class());
    }
}
