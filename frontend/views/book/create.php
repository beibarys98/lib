<?php

use common\models\Category;
use common\models\Type;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var $model2 */
/** @var $model3*/

$this->title = Yii::t('app', 'Добавить книгу');
?>
<div class="book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Заголовок')])->label(false) ?>

    <?= $form->field($model, 'authors')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Авторы')])->label(false) ?>

    <?php
    // Fetch categories from the database
    $types = Type::find()->all();
    $language = Yii::$app->language;
    $titleField = ($language === 'ru-RU') ? 'title_ru' : 'title_kz';
    $typeItems = ArrayHelper::map($types, 'id', $titleField);

    echo $form->field($model, 'type_id')->dropDownList(
        $typeItems,
        ['prompt' => Yii::t('app', 'Вид')]
    )->label(false);
    ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Издание')])->label(false) ?>

    <?= $form->field($model, 'release')->textInput(['placeholder' => Yii::t('app', 'Год')])->label(false) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'ISBN')])->label(false) ?>

    <?= $form->field($model, 'pages')->textInput(['placeholder' => Yii::t('app', 'Страницы')])->label(false) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(Yii::t('app', 'Описание')) ?>

    <?php
    // Fetch categories from the database
    $categories = Category::find()->all();
    $categoryItems = ArrayHelper::map($categories, 'id', 'title_kz');

    echo $form->field($model, 'category_id')->dropDownList(
        $categoryItems,
        ['prompt' => 'Категория']
    )->label(false);
    ?>

    <?= $form->field($model2, 'cover')->fileInput(['class' => 'form-control'])->label(Yii::t('app', 'Обложка')) ?>

    <?= $form->field($model3, 'book')->fileInput(['class' => 'form-control'])->label(Yii::t('app', 'Книга')) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
