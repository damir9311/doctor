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
}