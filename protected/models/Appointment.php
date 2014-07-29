<?php

/**
 * This is the model class for table "appointment".
 *
 * The followings are the available columns in table 'appointment':
 * @property string $appointment_id
 * @property string $user_name
 * @property string $employee_id
 * @property string $service_id
 * @property string $user_id
 * @property string $user_phone
 * @property string $appointment_start_time
 * @property string $appointment_end_time
 * @property string $appointment_cancelation
 * @property string $appointment_source
 *
 * The followings are the available model relations:
 * @property Employee $employee
 * @property Service $service
 * @property User $user
 */
class Appointment extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Appointment the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'appointment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_name, employee_id, service_id, user_id, user_phone, appointment_start_time, appointment_end_time, appointment_source', 'required'),
            array('user_name', 'length', 'max' => 200),
            array('employee_id, service_id, user_id', 'length', 'max' => 20),
            array('user_phone', 'length', 'max' => 40),
            array('appointment_cancelation, appointment_source', 'length', 'max' => 24),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('appointment_id, user_name, employee_id, service_id, user_id, user_phone, appointment_start_time, appointment_end_time, appointment_cancelation, appointment_source', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'service' => array(self::BELONGS_TO, 'Service', 'service_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'provider' => array(self::HAS_ONE, 'Provider', array('provider_id' => 'provider_id'), 'through' => 'service'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'appointment_id' => 'Appointment',
            'user_name' => 'User Name',
            'employee_id' => 'Employee',
            'service_id' => 'Service',
            'user_id' => 'User',
            'user_phone' => 'User Phone',
            'appointment_start_time' => 'Appointment Start Time',
            'appointment_end_time' => 'Appointment End Time',
            'appointment_cancelation' => 'Appointment Cancelation',
            'appointment_source' => 'Appointment Source',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('appointment_id', $this->appointment_id, true);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('employee_id', $this->employee_id, true);
        $criteria->compare('service_id', $this->service_id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('user_phone', $this->user_phone, true);
        $criteria->compare('appointment_start_time', $this->appointment_start_time, true);
        $criteria->compare('appointment_end_time', $this->appointment_end_time, true);
        $criteria->compare('appointment_cancelation', $this->appointment_cancelation, true);
        $criteria->compare('appointment_source', $this->appointment_source, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
//        $user = User::model()->findByPk($this->user_id);
        $date = date('Y-m-d', strtotime($this->appointment_start_time));
        $break_start = strtotime($date . ' ' . $this->employee->employee_breakstart);
        $break_end = strtotime($date . ' ' . $this->employee->employee_breakend);
        $work_start = strtotime($date . ' ' . $this->employee->employee_workstart);
        $work_end = strtotime($date . ' ' . $this->employee->employee_workend);

        $nextDate = date('Y-m-d', strtotime($date) + 60 * 60 * 24);
        $todaysAppointments = Appointment::model()->findAll("user_phone = $this->user_phone AND appointment_start_time >= '$date' AND appointment_start_time < '$nextDate' AND appointment_cancelation IS NULL");

        foreach ($todaysAppointments as $appointment) {
            if ($appointment->appointment_id != $this->appointment_id) {
                if ($appointment->service_id == $this->service_id) {
                    echo 'Error: You already booked this service today';
                    return false;
                }
                if (!((strtotime($this->appointment_start_time) <= strtotime($appointment->appointment_start_time)
                        && strtotime($this->appointment_end_time) <= strtotime($appointment->appointment_start_time))
                        ||
                        (strtotime($this->appointment_start_time) >= strtotime($appointment->appointment_end_time)
                        && strtotime($this->appointment_end_time) >= strtotime($appointment->appointment_end_time)))) {
                    //Checks that the timings are not during another appointment
                    echo 'Error: You already have another appointment at this time';
                    return false;
                }
            }
        }

        if (date('l', strtotime($this->appointment_start_time)) == $this->employee->employee_dayoff) {
            //Checks if it's the employee's day off
            echo 'Error: Cannot book on employee\'s day off';
            return false;
        } elseif (strtotime($this->appointment_start_time) < strtotime(date('Y-m-d H.i'))) {
            echo 'Error: Cannot book/cancel on a past time';
            return false;
        } elseif (!((strtotime($this->appointment_start_time) <= $break_start
                && strtotime($this->appointment_end_time) <= $break_start)
                ||
                (strtotime($this->appointment_start_time) >= $break_end
                && strtotime($this->appointment_end_time) >= $break_end))) {
            //Checks that the timings are not during his break;
            echo 'Error: Cannot book during breaktime';
            return false;
        } elseif (!(strtotime($this->appointment_start_time) >= $work_start
                && strtotime($this->appointment_end_time) <= $work_end)) {
            //Checks that the timings are within his working hours
            echo 'Error: Cannot book outside working hours';
            return false;
        } else {
            foreach ($this->employee->appointments as $appointment) {
                if (!$appointment->appointment_cancelation && $appointment->appointment_id != $this->appointment_id) {
                    if (!((strtotime($this->appointment_start_time) <= strtotime($appointment->appointment_start_time)
                            && strtotime($this->appointment_end_time) <= strtotime($appointment->appointment_start_time))
                            ||
                            (strtotime($this->appointment_start_time) >= strtotime($appointment->appointment_end_time)
                            && strtotime($this->appointment_end_time) >= strtotime($appointment->appointment_end_time)))) {
                        //Checks that the timings are not during another appointment
                        echo 'Error: Another appointment has already been booked';
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public function cancelAppointment($status) {
        if (!$this->appointment_cancelation) {
            $this->appointment_cancelation = $status;
            if ($this->save()) {
                echo 'Appointment cancelled successfully';
            } else {
                
            }
        } else {
            echo 'Error: Appointment has already been cancelled';
        }
    }

}