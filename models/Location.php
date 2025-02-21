<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "locations".
 *
 * @property int $id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property string $created_at
 *
 * @property WeatherData[] $weatherDatas
 */
class Location extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'locations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'latitude', 'longitude'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[WeatherDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWeatherDatas()
    {
        return $this->hasMany(WeatherData::class, ['location_id' => 'id']);
    }

}
