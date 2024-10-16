<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title_kz')->textInput(['maxlength' => true])->label(Yii::t('app', 'Заголовок Каз')) ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true])->label(Yii::t('app', 'Заголовок Рус')) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
