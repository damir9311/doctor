<?php

/* @var $this yii\web\View */
/* @var $doctor \app\models\Doctor */
/* @var $date DateTime */

use yii\helpers\Url;

$this->title = 'Запись на прием к врачу. Выберите время.';

$ajaxUrl = Url::to(['doctor/reserve']);
$reserveIdFormat = '999999999999999999';
$codeFormat = 'CODE';
$userDataUrl = Url::to([
    'doctor/user-data',
    'reserveId' => $reserveIdFormat,
    'code' => $codeFormat,
]);

$this->registerJs(
<<<JS
    var app = (function() {
        var 
            doctorId = {$doctor->id},
            date = '{$date->format('Y-m-d')}';
        return {
            reserveTime: function(el, time) {
                $.ajax({
                    type: "POST",
                    url: '{$ajaxUrl}',
                    data: 'doctor_id=' + doctorId + '&date=' + date + '&time=' + time,
                    success: function(data, textStatus, jqXHR) {
                        if (typeof data === 'object') {
                            if (data['reserve_id']) {
                                location.href = 
                                    '{$userDataUrl}'.replace('{$reserveIdFormat}', data['reserve_id']).replace('{$codeFormat}', data['code']);
                            } else {
                                alert('К сожалению, выбранное время уже занято!');
                                el.removeClass('active');
                                el.addClass('disabled');
                                el.find('.col-md-4').html('Занято');
                            }
                        } else {
                            alert('Что-то пошло не так, попробуйте повторить запрос.');
                        }
                    },
                    error: function(data) {
                        alert('Что-то пошло не так, попробуйте повторить запрос.');
                    },
                    dataType: 'json'
                });
            }
        };
    }());
    
    $(document).ready(function() {
        $('a.list-group-item').click(function(event) {
            event.preventDefault();
            if (!$(this).hasClass('disabled')) {
                $('a.list-group-item').removeClass('active');
                $(this).addClass('active');
            }
        });
        
        $('.reserve').click(function(event) {
            event.preventDefault();
            var el = $('a.list-group-item.active').first();
            if (el.length) {
                app.reserveTime(el, el.data('time'));
            } else {
                alert('Вы не выбрали время!');
            }
        });
    });
JS
);

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
            <div class="list-group">
                <?php foreach ($doctor->getSchedule($date) as $k => $time): ?>
                    <a
                        href="#"
                        class="list-group-item <?php echo ($time['busy'] === false ? 'list-group-item-success' : 'disabled'); ?>"
                        data-time="<?php echo $time['time']; ?>"
                    >
                        <div class="row">
                            <div class="col-md-8"><label><?php echo $time['time']; ?></label></div>
                            <div class="col-md-4">
                                <?php echo ($time['busy'] === false ? 'Свободно' : 'Занято'); ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="jumbotron">
            <a class="btn btn-lg btn-success reserve" href="#">Записаться &raquo;</a>
        </div>
    </div>
</div>
