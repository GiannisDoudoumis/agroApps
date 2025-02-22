<?php

namespace app\models;

use app\components\WeatherApiService;
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

    /**
     * Trigger an action after saving the model
     *
     * @param bool $insert Whether this is an insert or update
     * @param array $changedAttributes The changed attributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $service = new WeatherApiService();
            try {
                $service->fetchWeatherData($this->id);
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error',  $e->getMessage());

            }

        }
    }
}
