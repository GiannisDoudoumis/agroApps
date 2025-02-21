<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%locations}}`.
 */
class m250221_225836_create_locations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('locations', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'latitude' => $this->float()->notNull(),
            'longitude' => $this->float()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('locations');
    }
}
