<?php

namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class Doctor
 * @package app\models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \DateTime $added
 */
class Doctor extends ActiveRecord
{
    protected $_scheduleScheme = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doctors';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'added',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Проверяет свободен ли день для приема врачем
     * 
     * @param \DateTime $date
     * @return bool
     */
    public function isAvailableForDate($date)
    {
        $res = true;

        $scheduleScheme = $this->getScheduleScheme();
        $day = strtolower($date->format('l'));

        if (
            !empty($scheduleScheme['dayOffs']) &&
            in_array($day, $scheduleScheme['dayOffs'])
        ) {
            $res = false;
        }

        return $res;
    }

    /**
     * Проверяет свободное время на дату
     *
     * @param \DateTime $date
     * @param int $time
     * @return bool
     */
    public function isAvailableForDateTime($date, $time)
    {
        $res = false;
        if ($this->isAvailableForDate($date)) {
            $scheduleRecords = $this->getSchedule($date);
            foreach ($scheduleRecords as $scheduleRecord) {
                if ($scheduleRecord['time'] == $time && $scheduleRecord['busy'] === false) {
                    $res = true;
                    break;
                }
            }
        }
        return $res;
    }

    /**
     * Возращает шаблон расписания врача
     * 
     * @return array
     */
    public function getScheduleScheme()
    {
        if (!isset($this->_scheduleScheme)) {
            $this->_scheduleScheme = \Yii::$app->params['schedule'];
        }
        return $this->_scheduleScheme;
    }

    /**
     * Возвращает текущее расписание врача на дату
     *
     * @param \DateTime $date
     * @return array
     */
    public function getSchedule($date)
    {
        $now = new \DateTime('-' . Schedule::RESERVING_DELAY);
        $scheduleRecords = Schedule::find()->where(
            [
                'and',
                [
                    'and',
                    ['doctor_id' => $this->id],
                    ['date' => $date->format('Y-m-d')],
                ],
                [
                    'or',
                    ['reserve_status' => Schedule::RESERVE_STATUS_RESERVED],
                    [
                        'and',
                        ['reserve_status' => Schedule::RESERVE_STATUS_RESERVING],
                        ['>=', 'added', $now->format('Y-m-d H:i:s')]
                    ]
                ],
            ]
        )->asArray()->all();
        $scheduleRecordsA = [];
        foreach ($scheduleRecords as $scheduleRecord) {
            $key = Schedule::formatTimeToString($scheduleRecord['time_from']) .
                '-' . Schedule::formatTimeToString($scheduleRecord['time_to']);
            $scheduleRecordsA[$key] = $scheduleRecord;
        }

        $schedule = [];
        $scheduleScheme = $this->getScheduleScheme();
        $day = strtolower($date->format('l'));
        foreach ($scheduleScheme as $d => $times) {
            if ($d != $day) {
                continue;
            }
            foreach ($times as $time) {
                $schedule[] = [
                    'time' => $time,
                    'busy' => isset($scheduleRecordsA[$time]),
                ];
            }
        }
        return $schedule;
    }

    /**
     * Помечает время как зарезервированное на время 
     * (до полного бронирования, т.е. заполнение всей формы).
     * 
     * @param \DateTime $date
     * @param string $time
     * @return Schedule
     */
    public function startReserveTime($date, $time)
    {
        $schedule = new Schedule();
        $schedule->doctor_id = $this->id;
        $schedule->reserve_status = Schedule::RESERVE_STATUS_RESERVING;
        $schedule->date = $date->format('Y-m-d');
        $schedule->setTime($time);
        $schedule->save();
        return $schedule;
    }

}