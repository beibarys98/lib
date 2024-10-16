<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var $dataProvider*/

$this->title = $model->title;
\yii\web\YiiAsset::register($this);

$titleField = (Yii::$app->language === 'kz-KZ') ? 'category.title_kz' : 'category.title_ru';
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => $titleField,
                'label' => 'Категория'
            ],
            [
                'attribute' => 'title',
                'label' => Yii::t('app', 'Заголовок')
            ],
            [
                'attribute' => 'authors',
                'label' => Yii::t('app', 'Авторы')
            ],
            [
                'attribute' => 'publisher',
                'label' => Yii::t('app', 'Издание')
            ],
            [
                'attribute' => 'release',
                'label' => Yii::t('app', 'Год')
            ],
            [
                'attribute' => 'isbn',
                'label' => Yii::t('app', 'ISBN')
            ],
            [
                'attribute' => 'pages',
                'label' => Yii::t('app', 'Страницы')
            ],
            [
                'attribute' => 'description',
                'format' => 'raw',
                'label' => Yii::t('app', 'Описание')
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => Yii::t('app', 'Дата создания')
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'label' => Yii::t('app', 'Дата изменения')
            ]
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'layout' => '{items}',
        'columns' => [
            [
                'attribute' => 'file_path',
                'format' => 'raw',
                'value' => function($model) {
                    if (preg_match('/\.(jpg|jpeg|png)$/i', $model->file_path)) {
                        if (strpos($model->file_path, '/app') === 0) {
                            $file_path = preg_replace('/.*\/([^\/]+)$/', '$1', $model->file_path);
                            $fileUrl = Url::to('@web/uploads/covers/' . $file_path);
                        }else{
                            $fileUrl = Url::to('@web/uploads/covers' . $model->file_path);
                        }
                        return Html::a(Yii::t('app', 'Обложка'), $fileUrl, ['target' => '_blank']);
                    } elseif (preg_match('/\.pdf$/i', $model->file_path)) {
                        if(strpos($model->file_path, '/app') === 0){
                            $file_path = preg_replace('/.*\/([^\/]+)$/', '$1', $model->file_path);
                            $fileUrl = Url::to('@web/uploads/books/' . $file_path);
                        }else{
                            $fileUrl = Url::to('@web/uploads/books' . $model->file_path);
                        }
                        return Html::a(Yii::t('app', 'Книга'), $fileUrl, ['target' => '_blank']);
                    } else {
                        return '';
                    }
                },
            ],
        ],
    ]); ?>

</div>
