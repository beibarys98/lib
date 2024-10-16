<?php

/** @var yii\web\View $this */
/**
 * @var $book
 */

use common\models\Storage;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Baishev Lib';
?>
<div class="site-index">
    <div class="row">
        <div class="col-3 text-center">
            <?php
            $cover = Storage::find()
                ->andWhere(['model_id' => $book->id])
                ->andWhere(['or',
                    ['like', 'file_path', '%.jpg', false],
                    ['like', 'file_path', '%.jpeg', false],
                    ['like', 'file_path', '%.png', false]
                ])->one();
            if($cover){
                if (strpos($cover->file_path, '/app') === 0) {
                    $imagePath = Yii::getAlias('@web/uploads/covers/') . basename($cover->file_path);
                } else {
                    $imagePath = Yii::getAlias('@web/uploads/covers') . $cover->file_path;
                }
                $imageTag = Html::img($imagePath, ['class' => 'img-thumbnail', 'style' => 'width: 100%; height: auto;']);
                echo Html::tag('div', $imageTag, ['style' => 'height: 370px; overflow: hidden;']);
            }

            ?>
            <br>
            <?= Html::a('Оқу', ['read', 'id' => $book->id], ['class' => 'btn btn-primary w-100', 'target' => '_blank']) ?>
        </div>
        <div class="col-9">
            <div style="font-size: 36px;">
                <?= Html::encode($book->title) ?>
            </div>
            <br>
            <div style="font-size: 24px;">
                <?= 'Автор(лар)ы: '.Html::encode($book->authors)?>
                <br>
                <?= 'Баспа: '.Html::encode($book->publisher)?>
                <br>
                <?= 'Бет саны: '.Html::encode($book->pages)?>
                <br>
                <?= 'Шыққан жылы: '.Html::encode($book->release)?>
                <br>
                <?= 'ISBN: '.Html::encode($book->isbn)?>
                <br>
                <br>
                <?= 'Сипаттамасы'?>
                <br>
                <?= $book->description ?>
            </div>

        </div>
    </div>
</div>