<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%weather_data}}`.
 */
class m250221_225953_create_weather_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('weather_data', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'api_source' => $this->string(255)->notNull(),
            'date' => $this->date()->notNull(),
            'hourly_data' => $this->json()->notNull(),
            'daily_data' => $this->json()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Foreign key to locations table
        $this->addForeignKey(
            'fk-weather_data-location_id',
            'weather_data',
            'location_id',
            'locations',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-weather_data-location_id', 'weather_data');
        $this->dropTable('weather_data');
    }
}
