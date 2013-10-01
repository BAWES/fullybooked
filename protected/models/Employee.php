<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property string $employee_id
 * @property string $branch_id
 * @property string $employee_name
 * @property string $employee_workstart
 * @property string $employee_workend
 * @property string $employee_breakstart
 * @property string $employee_breakend
 * @property string $employee_dayoff
 *
 * The followings are the available model relations:
 * @property Appointment[] $appointments
 * @property Branch $branch
 * @property Service[] $services
 */
class Employee extends CActiveRecord {

    public $branch_search;
    public $services_input = array(); //temporary variable used for MANY MANY crud

    public function behaviors() {
        return array(
            'activerecord-relation' => array(
                'class' => 'ext.yiiext.behaviors.activerecord-relation.EActiveRecordRelationBehavior',
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Employee the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'employee';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('branch_id, employee_name, employee_workstart, employee_workend, employee_breakstart, employee_breakend, employee_dayoff', 'required'),
            array('branch_id', 'length', 'max' => 20),
            array('employee_name', 'length', 'max' => 160),
            array('employee_dayoff', 'length', 'max' => 64),
            array('employee_workstart', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_workend', 'operator' => '<',
                'allowEmpty' => false, 'message' => 'Work start time must be before work ends'),
            //validate breakstart
            //make sure break starts before break ends
            array('employee_breakstart', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_breakend', 'operator' => '<',
                'allowEmpty' => false, 'message' => 'Break start time must be before break ends'),
            //make sure break starts before work ends
            array('employee_breakstart', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_workend', 'operator' => '<',
                'allowEmpty' => false, 'message' => 'Break start time must be before work ends'),
            //make sure break starts after work starts
            array('employee_breakstart', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_workstart', 'operator' => '>',
                'allowEmpty' => false, 'message' => 'Break start time must be after work starts'),
            //validate breakend
            //make sure break ends before work ends
            array('employee_breakend', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_workend', 'operator' => '<',
                'allowEmpty' => false, 'message' => 'Break end time must be before work ends'),
            //make sure break ends after work starts
            array('employee_breakend', 'ext.timevalidator.TimeValidator', 'compareAttribute' => 'employee_workstart', 'operator' => '>',
                'allowEmpty' => false, 'message' => 'Break end time must be after work starts'),
            //validate checkbox input that all are integer to avoid sql injection
            //(optional) use "exist" validator aswell to make sure keys exist in other tables
            //but not neccessary as constraints are set on db
            array('services_input', 'ext.arrayvalidator.ArrayValidator', 'validator' => 'numerical', 'params' => array('integerOnly' => true)),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('employee_id, branch_search, branch_id, employee_name, employee_workstart, employee_workend, employee_breakstart, employee_breakend, employee_dayoff', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'appointments' => array(self::HAS_MANY, 'Appointment', 'employee_id'),
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'provider' => array(self::HAS_ONE, 'Provider', array('provider_id' => 'provider_id'), 'through' => 'branch'),
            'services' => array(self::MANY_MANY, 'Service', 'employeeservice(employee_id, service_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'employee_id' => 'Employee ID',
            'branch_id' => 'Branch',
            'employee_name' => 'Name',
            'employee_workstart' => 'Work start',
            'employee_workend' => 'Work end',
            'employee_breakstart' => 'Break start',
            'employee_breakend' => 'Break end',
            'employee_dayoff' => 'Day off',
            'branch_search' => 'Branch',
            'services_input' => 'Services',
        );
    }


    /**
     * @return CDbExpression if input contains (AM/PM)
     */
    private function convertTimeInput($timeInput, $unique) {
        if ((strpos($timeInput, 'am') !== false) || strpos($timeInput, 'pm') !== false) {
            //convert 12 hr format to 24
            return new CDbExpression("TIME(STR_TO_DATE(:time$unique,'%h:%i %p'))",
                            array(":time$unique" => $timeInput));
        }else
            return $timeInput;
    }

    private function convertTimeOutput($timeInput) { //convert 24hr sql to 12hr readable
        return date('g:i a', strtotime($timeInput));
    }

    protected function beforeSave() {
        //check the timings if they have AM or PM, set as CDBExpression that converts to sql 24hr format.
        $this->employee_workstart = $this->convertTimeInput($this->employee_workstart, 1);
        $this->employee_workend = $this->convertTimeInput($this->employee_workend, 2);
        $this->employee_breakstart = $this->convertTimeInput($this->employee_breakstart, 3);
        $this->employee_breakend = $this->convertTimeInput($this->employee_breakend, 4);

        //take $services_input and use the cactiverecord behavior to populate the many many
        $this->services = $this->services_input;

        return parent::beforeSave();
    }

    protected function afterFind() {
        // convert to display format
        $this->employee_workstart = $this->convertTimeOutput($this->employee_workstart);
        $this->employee_workend = $this->convertTimeOutput($this->employee_workend);
        $this->employee_breakstart = $this->convertTimeOutput($this->employee_breakstart);
        $this->employee_breakend = $this->convertTimeOutput($this->employee_breakend);

        //populate $services_input with the services currently offered by employee (for checkboxes default value)
        if (!empty($this->services)) {
            foreach ($this->services as $service) //add each providers id to the array
                $this->services_input[] = $service->service_id;
        }

        parent::afterFind();
    }

    protected function beforeDelete() {
        if (parent::beforeDelete()) {
            //delete services offered by Employee
            EmployeeService::model()->find("employee_id=" . $this->employee_id)->delete();

            return true;
        }
        else
            return false;
    }

    //shows employees available on a specific day (ex: "Saturday")
    //defaults to current day (today)
    public function available($date) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "employee_dayoff != '$date'",
        ));
        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($providerID = false) { //$providerID to only load employees under that provider
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;

        if ($providerID) {
            $criteria->with = array(
                'branch',
                'provider' => array(
                    'select' => false,
                    'condition' => "provider.provider_id=" . $providerID,
                ),
            );
        }

        $criteria->compare('employee_id', $this->employee_id, true);
        $criteria->compare('branch_id', $this->branch_id, true);
        $criteria->compare('employee_name', $this->employee_name, true);
        $criteria->compare('employee_workstart', $this->employee_workstart, true);
        $criteria->compare('employee_workend', $this->employee_workend, true);
        $criteria->compare('employee_breakstart', $this->employee_breakstart, true);
        $criteria->compare('employee_breakend', $this->employee_breakend, true);
        $criteria->compare('employee_dayoff', $this->employee_dayoff, true);

        //Add search function to related
        $criteria->compare('branch.branch_address', $this->branch_search, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(//add sorting to related model
                        'attributes' => array(
                            'branch_search' => array(
                                'asc' => 'branch.branch_address',
                                'desc' => 'branch.branch_address DESC',
                            ),
                            '*', //* means treat other fields normally
                        ),
                    ),
                ));
    }

}