<?php

use common\models\Category;
use common\models\Storage;
use common\models\Type;
use yii\bootstrap5\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var $model2 */
/** @var $model3*/
/** @var $dataProvider*/

$this->title = Yii::t('app', '{name}', [
    'name' => $model->title,
]);
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(Yii::t('app', 'Заголовок')) ?>

    <?= $form->field($model, 'authors')->textInput(['maxlength' => true])->label(Yii::t('app', 'Авторы')) ?>

    <?php
    // Fetch categories from the database
    $types = Type::find()->all();
    $typeItems = ArrayHelper::map($types, 'id', 'title_kz');

    echo $form->field($model, 'type_id')->dropDownList(
        $typeItems,
        ['prompt' => Yii::t('app', 'Вид')]
    )->label(Yii::t('app', 'Вид'));
    ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true])->label(Yii::t('app', 'Издание')) ?>

    <?= $form->field($model, 'release')->textInput()->label(Yii::t('app', 'Год')) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true])->label('ISBN') ?>

    <?= $form->field($model, 'pages')->textInput()->label(Yii::t('app', 'Страницы')) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(Yii::t('app', 'Описание')) ?>

    <?php
    // Fetch categories from the database
    $categories = Category::find()->all();
    $categoryItems = ArrayHelper::map($categories, 'id', 'title_kz');

    echo $form->field($model, 'category_id')->dropDownList(
        $categoryItems,
        ['prompt' => 'Категория']
    )->label(Yii::t('app', 'Категория'));
    ?>

    <?php if($model2->model_id == null):?>
    <?= $form->field($model2, 'cover')->fileInput(['class' => 'form-control'])->label(Yii::t('app', 'Обложка')) ?>
    <?php endif;?>

    <?php if($model3->model_id == null):?>
    <?= $form->field($model3, 'book')->fileInput(['class' => 'form-control'])->label(Yii::t('app', 'Книга')) ?>
    <?php endif;?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'layout' => '{items}',
        'columns' => [
            [
                'attribute' => 'file_path',
                'format' => 'raw',
                'value' => function($model) {
                    if (preg_match('/\.jpg$/i', $model->file_path)) {
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
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Storage $model, $key, $index, $column) {
                    return Url::toRoute(['storage/delete', 'id' => $model->id]);
                },
            ]
        ],
    ]); ?>

</div>
