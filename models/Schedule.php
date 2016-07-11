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
}