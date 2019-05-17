<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Синтез речи';
?>
<div class="site-index">


    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <p>Перед использованием сайта обязательно ознакомтесь с <a href="https://yandex.ru/legal/cloud_termsofuse/?lang=ru" target="_blank">пользовательским соглашением</a> сервиса Яндекс.Облако</p>
            </div>
        </div>


        <div class="row">

            <?php Pjax::begin([
                    'id' => 'tts',
                ]); ?>


                <?php
                    $form = ActiveForm::begin([
                        'options' => [
                            'class' => 'col-lg-4 col-xs-12',
                            'enctype' => 'multipart/form-data'
                        ],
                    ]);
                    ?>
                    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'voice')->dropDownList([
                        'alyss' => 'alyss',
                        'oksana' => 'oksana',
                        'jane' => 'jane',
                        'omazh' => 'omazh',
                        'zahar' => 'zahar',
                        'ermil' => 'ermil',
                    ],[
                        'prompt' => 'Выберите голос',
                    ]) ?>
                <?= $form->field($model, 'lang')->dropDownList([
                    'ru-RU' => 'Русский',
                    'en-US' => 'Английский',
                    'tr-TR' => 'Турецкий',
                ],[
                    'prompt' => 'Выберите язык',
                ]) ?>

                <?= $form->field($model, 'emotion')->dropDownList([
                    'neutral' => 'Нейтрально',
                    'good' => 'Радостно, доброжелательно',
                    'evil' => 'Раздраженно',
                ],[
                    'prompt' => 'Выберите эмоциональную окраску',
                ]) ?>
                <?= $form->field($model, 'speed')->textInput() ?>

                <?= $form->field($model, 'oauth_key')->textInput() ?>

                <?= $form->field($model, 'folder_id')->textInput() ?>


                <?= Html::submitButton('Синтезировать', ['class' => 'btn btn-primary']) ?>

                <?php ActiveForm::end(); ?>


            <?php if (isset($file)): ?>

                <div class="col-xs-12 col-lg-4" style="padding-top: 24px;">
                    <audio controls>
                        <source src="/site/file?path=<?= urlencode($file) ?>" type="audio/wav">
                    </audio>
                    <p>Если запись не воспроизводится, попробуйте <a data-pjax=0 href="/site/file?path=<?= urlencode($file) ?>">скачать</a> ее</p>
                </div>

            <?php endif; ?>


             <?php Pjax::end(); ?>


        </div>
    </div>





</div>
