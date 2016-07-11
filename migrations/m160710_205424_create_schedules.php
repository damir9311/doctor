<?php

use yii\db\Migration;

/**
 * Handles the creation for table `schedules`.
 */
class m160710_205424_create_schedules extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('schedules', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer(),
            'date' => $this->date(),
            'time_from' => $this->integer(),
            'time_to' => $this->integer(),
            'client_data' => $this->text(),
            'reserve_status' => $this->integer(1)->defaultValue(1),
            'added' => $this->dateTime()->notNull(),
        ]);
        $this->addForeignKey('doctor', 'schedules', 'doctor_id', 'doctors', 'id');
        $this->createIndex('index1', 'schedules', ['doctor_id', 'date', 'reserve_status']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('schedules');
    }
}
