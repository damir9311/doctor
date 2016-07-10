<?php

/* @var $this yii\web\View */
/* @var $schedule array */
/* @var $doctor \app\models\Doctor */

use yii\helpers\Url;

$this->title = 'Запись на прием к врачу. Выберите дату.';

if (!empty($schedule['dayOffs'])) {
    $dayOffs = join(': 1, ', $schedule['dayOffs']) . ': 1';
} else {
    $dayOffs = '';
}
$dateFormat = '0000-00-00';
$doctorTimeSelectUrl = Url::to(['doctor/time', 'doctorId' => $doctor->id, 'date' => $dateFormat]);

$this->registerCssFile('@web/css/fullcalendar.min.css');
$this->registerJsFile('@web/js/moment.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/fullcalendar.min.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('@web/js/ru.js', ['depends' => 'yii\web\JqueryAsset']);

$this->registerJs(
<<<JS
    $(document).ready(function() {
        var weekdays = { 
            0: "sunday",
            1: "monday",
            2: "tuesday",
            3: "wednesday",
            4: "thursday",
            5: "friday",
            6: "saturday",
        };
        var dayOffs = {{$dayOffs}};
        var selectedDate = null;
    
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: [],
            dayRender: function(date, cell) {
                if (dayOffs[weekdays[date.toDate().getDay()]]) {
                    cell.css("background-color", "#ffb3b3");
                }
            },
            dayClick: function(date, jsEvent, view) {
                if (dayOffs[weekdays[date.toDate().getDay()]]) {
                    alert('К сожалению, выбранная дата не доступна!');
                } else {
                    $(".fc-state-highlight2").removeClass("fc-state-highlight2");
                    $(jsEvent.target).addClass("fc-state-highlight2");
                    selectedDate = date.toDate();
                }
            }
        });
        
        $('.select-time').click(function() {
            if (selectedDate) {
                location.href = 
                    '{$doctorTimeSelectUrl}'.replace('{$dateFormat}', selectedDate.toISOString().slice(0, 10));
            } else {
                alert('Вы еще не выбрали день!');
            }
            return false;
        });
    });
JS
);
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Выберите дату</h1>
    </div>
    <div class="body-content">
        <div><p class="lead"><?php echo $doctor->name ?></p></div>
        <div><p class="lead"><?php echo $doctor->description ?></p></div>

        <div id='calendar'></div>

        <div class="jumbotron">
            <a class="btn btn-lg btn-success select-time" href="#">Выбрать время &raquo;</a>
        </div>
    </div>
</div>