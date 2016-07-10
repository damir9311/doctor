<?php

/* @var $this yii\web\View */
/* @var $doctors app\models\Doctor[] */

use yii\helpers\Url;

$this->title = 'Запись на прием к врачу. Выберите доктора';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Запись на прием к врачу</h1>
        <p class="lead">Выберите нужного Вам врача и нажмите "Записаться".</p>
    </div>
    <div class="body-content">
        <div class="row">
            <?php foreach ($doctors as $doctor) : ?>
                <div class="col-lg-4">
                    <h2><?php echo $doctor->name ?></h2>
                    <p><?php echo $doctor->description ?></p>
                    <p><a class="btn btn-default" href="<?php echo Url::to(['doctor/calendar', 'doctorId' => $doctor->id]); ?>">Записаться &raquo;</a></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
