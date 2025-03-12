<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "appointments".
 *
 * @property int $appointment_id
 * @property int $user_id
 * @property string $scheduled_date
 * @property string $scheduled_time
 * @property string $appointment_type
 * @property string $status
 * @property string $created_at
 */
class Appointment extends ActiveRecord
{
    /**
     * Define table name
     */
    public static function tableName()
    {
        return 'appointments';
    }

    /**
     * Define validation rules
     */
    public function rules()
    {
        return [
            [['user_id', 'scheduled_date', 'scheduled_time', 'appointment_type', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['scheduled_date', 'scheduled_time', 'created_at'], 'safe'],
            [['appointment_type', 'status'], 'string', 'max' => 255],
            [['user_id', 'scheduled_date', 'scheduled_time'], 'unique', 'targetAttribute' => ['user_id', 'scheduled_date', 'scheduled_time'], 'message' => 'The selected date and time is already booked.'],
        ];
    }

    /**
     * Define attribute labels
     */
    public function attributeLabels()
    {
        return [
            'appointment_id' => 'Appointment ID',
            'user_id' => 'User ID',
            'scheduled_date' => 'Scheduled Date',
            'scheduled_time' => 'Scheduled Time',
            'appointment_type' => 'Appointment Type',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Automatically set created_at before saving
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }
}
