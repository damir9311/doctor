<?php

use yii\db\Migration;

/**
 * Handles the creation for table `doctors`.
 */
class m160707_193758_create_doctors extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('doctors', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'description' => $this->text(),
            'added' => $this->dateTime()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('doctors');
    }
}
