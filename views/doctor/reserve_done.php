<?php

/* @var $this yii\web\View */
/* @var $scheduleRecord app\models\Schedule */
/* @var $doctor app\models\Doctor */

use app\models\Schedule;


$this->title = 'Запись на прием к врачу. Вы записаны!';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Вы записаны!</h1>
    </div>
    <div class="body-content">
        <div><p class="lead"><?php echo $doctor->name; ?></p></div>
        <div><p class="lead"><?php echo $doctor->description; ?></p></div>
        <div><p class="lead">Дата приема: <?php echo DateTime::createFromFormat('Y-m-d', $scheduleRecord->date)->format('d.m.Y') ; ?></p></div>
        <div>
            <p class="lead">
                Время приема: <?php echo Schedule::formatTimeToString($scheduleRecord->time_from) ; ?> до
                <?php echo Schedule::formatTimeToString($scheduleRecord->time_to) ; ?>
            </p>
        </div>
        <h2>Ждем Вас!</h2>
    </div>
</div>