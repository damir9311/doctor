<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class Schedule
 * @package app\models
 *
 * @property int $id
 * @property int $doctor_id
 * @property \DateTime $date
 * @property int $time_from
 * @property int $time_to
 * @property string $client_data
 * @property int $reserve_status
 * @property \DateTime $added
 */
class Schedule extends ActiveRecord
{
    const RESERVE_STATUS_RESERVING = 1;
    const RESERVE_STATUS_RESERVED = 2;
    const RESERVING_DELAY = '5 min';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedules';
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

    // TODO: сделать валидацию полей $time_from, $time_to, $reserve_status
    // TODO: прописать belongs_to и has_many

    /**
     * Заполняет поля $time_from и $time_to из строки вида "10:00-11:00"
     *
     * @param string $time
     */
    public function setTime($time)
    {
        list($from, $to) = explode('-', $time);
        $this->time_from = static::formatTimeFromString($from);
        $this->time_to = static::formatTimeFromString($to);
    }

    /**
     * Форматирует числовое представление времени в читаемый вид
     *
     * @param int $time
     * @return string
     */
    public static function formatTimeToString($time)
    {
        return substr_replace(sprintf("%'.04d", $time), ':', 2, 0);
    }
    
    /**
     * Форматирует строковое представление времени в числовое
     *
     * @param string $time
     * @return int
     */
    public static function formatTimeFromString($time)
    {
        return intval(str_replace(':', '', $time));
    }

    /**
     * Проверяет доступна ли запись расписания для брони
     * @return bool
     */
    public function isAvailableForReserve()
    {
        $now = new \DateTime('-' . self::RESERVING_DELAY);
        return !self::find()->where(
            [
                'and',
                [
                    'and',
                    ['doctor_id' => $this->doctor_id],
                    ['date' => $this->date],
                    ['time_from' => $this->time_from],
                    ['time_to' => $this->time_to],
                    ['<>', 'id', $this->id]
                ],
                [
                    'or',
                    ['reserve_status' => self::RESERVE_STATUS_RESERVED],
                    [
                        'and',
                        ['reserve_status' => self::RESERVE_STATUS_RESERVING],
                        ['>=', 'added', $now->format('Y-m-d H:i:s')]
                    ]
                ],
            ]
        )->exists();
    }

    /**
     * Возвращает данные клиента, который записался на данное время
     *
     * @return array
     */
    public function getClientData()
    {
        return json_decode($this->client_data);
    }
}