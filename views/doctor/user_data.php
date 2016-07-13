<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $clientData app\models\ClientDataForm */
/* @var $scheduleRecord app\models\Schedule */
/* @var $doctor app\models\Doctor */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Запись на прием к врачу. Осталось чуть-чуть.';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Введите Ваши данные для записи к врачу</h1>
    </div>
    <div class="body-content">
        <div><p class="lead"><?php echo $doctor->name; ?></p></div>
        <div><p class="lead"><?php echo $doctor->description; ?></p></div>
        <div><p class="lead">Дата приема: <?php echo DateTime::createFromFormat('Y-m-d', $scheduleRecord->date)->format('d.m.Y') ; ?></p></div>

        <?php $form = ActiveForm::begin([
            'id' => 'reserve-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?php echo $form->field($clientData, 'name')->textInput(['autofocus' => true]); ?>

        <?php echo $form->field($clientData, 'phone')->textInput(); ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?php echo Html::submitButton('Записаться', ['class' => 'btn btn-lg btn-success', 'name' => 'reserve-button']); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>