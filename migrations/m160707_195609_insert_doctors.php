<?php

use yii\db\Migration;

class m160707_195609_insert_doctors extends Migration
{
    public function up()
    {
        $names = ['Иван', 'Екатерина', 'Петр', 'Николай', 'Александр'];
        $lastNames = ['Иванов', 'Петров', 'Сидоров', 'Матвеева'];
        $descriptions = [
            'Врач офтальмолог 1 категории',
            'Педиатр', 
            'Хирург', 
            'Детский педиатр', 
            'Психолог'
        ];
        $doctorsNum = mt_rand(3, 6);
        for ($i = 1; $i <= $doctorsNum; $i ++) {
            $doctor = new \app\models\Doctor();
            $doctor->name =
                $names[mt_rand(0, count($names) - 1)] . ' ' .
                $lastNames[mt_rand(0, count($lastNames) - 1)];
            $doctor->description =
                $descriptions[mt_rand(0, count($descriptions) - 1)];
            $doctor->save();
        }
    }

    public function down()
    {
        echo "m160707_195609_insert_doctors cannot be reverted.\n";
    }
}
