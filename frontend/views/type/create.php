<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Type $model */

$this->title = Yii::t('app', 'Добавить вид');
?>
<div class="type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
