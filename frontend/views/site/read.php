<?php

/** @var yii\web\View $this */
/**
 * @var $file
 * @var $filePath
 */

echo \diecoding\pdfjs\PdfJs::widget([
    'url' => \yii\helpers\Url::to($filePath),
    'options' => [
        'style' => [
            'width' => '100%',
            'height' => '900px',
        ],
    ],
]);
?>


