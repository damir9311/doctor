<?php

/* @var $doctor \app\models\Doctor */
/* @var $date DateTime */

$this->title = 'Запись на прием к врачу. Выберите время.';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Выберите время</h1>
    </div>
    <div class="body-content">
        <div><p class="lead"><?php echo $doctor->name ?></p></div>
        <div><p class="lead"><?php echo $doctor->description ?></p></div>
        <div><p class="lead">Дата приема: <?php echo $date->format('d.m.Y') ?></p></div>

        <div class="schedule">
            <ul class="list-group">
                <?php foreach ($doctor->getSchedule($date) as $k => $time) : ?>
                    <li class="list-group-item <?php echo ($time['busy'] === false ? 'list-group-item-success' : 'list-group-item-warning'); ?>">
                        <div class="row">
                            <div class="col-md-8"><label><?php echo $time['from']; ?> - <?php echo $time['to']; ?></label></div>
                            <div class="col-md-4">
                                <?php if ($time['busy']) : ?>
                                    Занято
                                <?php else: ?>
                                    Свободно
                                <?php endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>
