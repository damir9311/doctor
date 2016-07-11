<?php

use yii\db\Migration;

class m160710_211117_insert_schedules extends Migration
{
    public function up()
    {
        $clientData = [
            'name' => 'Иванов Иван Петрович',
            'email' => 'ivanov@mail.mail',
            'phone' => '9172345678'
        ];
        
        /** @var app\models\Doctor[] $doctors */
        $doctors = app\models\Doctor::find()->all();
        foreach ($doctors as $doctor) {
            $scheduleScheme = $doctor->getScheduleScheme();
            for ($i = 0; $i <= 30; $i ++) {
                $date = new DateTime();
                $interval = DateInterval::createFromDateString('+' . $i . ' day');
                $date->add($interval);
                $w = strtolower($date->format('l'));
                if (!empty($scheduleScheme[$w])) {
                    $time = $scheduleScheme[$w][mt_rand(0, count($scheduleScheme[$w]) - 1)];
                    list($from, $to) = explode('-', $time);
                    $schedule = new \app\models\Schedule();
                    $schedule->doctor_id = $doctor->id;
                    $schedule->date = $date->format('Y-m-d');
                    $schedule->time_from = str_replace(':', '', $from);
                    $schedule->time_to = str_replace(':', '', $to);
                    $schedule->reserve_status = \app\models\Schedule::RESERVE_STATUS_RESERVED;
                    $schedule->client_data = json_encode($clientData);
                    $schedule->save();
                }
            }
        }
    }

    public function down()
    {
        echo "m160710_211117_insert_schedules cannot be reverted.\n";

        //return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
