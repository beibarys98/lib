<?php

/** @var yii\web\View $this */
/**
 * @var $category
 * @var $book
 */

use common\models\Storage;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Baishev Lib';
?>
<div class="site-index">
    <div class="row">
        <div class="col-3">
            <ul class="list-group shadow">
                <?php foreach ($category->query->all() as $ctg): ?>
                    <a href="<?= Url::to(['/site/index', 'ctg' => $ctg->id]) ?>"
                       class="list-group-item list-group-item-action
                       <?= (Yii::$app->request->get('ctg') == $ctg->id) ? 'active' : '' ?>">
                        <?= Yii::$app->language == 'kz-KZ' ? $ctg->title_kz : $ctg->title_ru ?>
                    </a>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-9">
            <div class="book-index">

                <?= ListView::widget([
                    'dataProvider' => $book,
                    'itemOptions' => ['class' => 'col-md-3'],
                    'layout' => "<div class='row'>{items}</div>",
                    'itemView' => function ($model) {
                        $cover = Storage::find()
                            ->andWhere(['model_id' => $model->id])
                            ->andWhere(['or',
                                ['like', 'file_path', '%.jpg', false],
                                ['like', 'file_path', '%.jpeg', false],
                                ['like', 'file_path', '%.png', false]
                            ])->one();
                        if($cover){
                            if (strpos($cover->file_path, '/app') === 0) {
                                $imagePath = Yii::getAlias('@web/uploads/covers/') . basename($cover->file_path);
                            } else {
                                $imagePath = Yii::getAlias('@web/uploads/covers')
                                    . $cover->file_path;
                            }
                            $imageTag = Html::img($imagePath,
                                ['class' => 'img-thumbnail']);
                            $linkContent = Html::tag('div', $imageTag,
                                ['style' => 'height: 240px; overflow: hidden;']);
                            $title = Html::tag('div',
                                Html::encode($model->title),
                                ['style' => 'max-height: 50px; overflow: hidden; font-weight: bold;']
                            );
                            $authors = Html::tag('div', Html::encode($model->authors),
                                ['style' => 'max-height: 50px; overflow: hidden;']);
                        }else{
                            $linkContent = Html::tag('div', null,
                                ['style' => 'height: 240px; overflow: hidden;']);
                            $title = Html::tag('div',
                                Html::encode($model->title),
                                ['style' => 'max-height: 50px; overflow: hidden; font-weight: bold;']
                            );
                            $authors = Html::tag('div', Html::encode($model->authors),
                                ['style' => 'max-height: 50px; overflow: hidden;']);
                        }

                        return Html::a($linkContent . $title . $authors,
                            ['view', 'id' => $model->id],
                            ['class' => 'list-group-item list-group-item-action shadow']);
                    },
                ]); ?>

            </div>
        </div>
    </div>
</div>

<style>
    .book-index .row {
        display: flex;
        flex-wrap: wrap;
    }
    .book-index .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
        padding: 10px;
    }
    .book-index .list-group-item {
        display: block;
        margin-bottom: 10px;
        text-align: center;
        border: 1px solid black;
        padding: 10px;
        border-radius: 10px;
        height: 350px;
    }
</style>