<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property string $service_id
 * @property string $provider_id
 * @property integer $service_duration
 * @property double $service_price
 * @property string $service_name
 * @property string $service_description
 *
 * The followings are the available model relations:
 * @property Appointment[] $appointments
 * @property Employee[] $employees
 * @property Provider $provider
 */
class Service extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Service the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('provider_id, service_duration, service_price, service_name, service_description', 'required'),
			array('service_duration', 'numerical', 'integerOnly'=>true),
			array('service_price', 'numerical'),
			array('provider_id', 'length', 'max'=>20),
			array('service_name', 'length', 'max'=>160),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('service_id, provider_id, service_duration, service_price, service_name, service_description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'appointments' => array(self::HAS_MANY, 'Appointment', 'service_id'),
			'employees' => array(self::MANY_MANY, 'Employee', 'employeeservice(service_id, employee_id)'),
			'provider' => array(self::BELONGS_TO, 'Provider', 'provider_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'service_id' => 'ID',
			'provider_id' => 'Provider',
			'service_duration' => 'Duration',
			'service_price' => 'Price',
			'service_name' => 'Service',
			'service_description' => 'Description',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($condition="")
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->condition = $condition;

		$criteria->compare('service_id',$this->service_id,true);
		$criteria->compare('provider_id',$this->provider_id,true);
		$criteria->compare('service_duration',$this->service_duration);
		$criteria->compare('service_price',$this->service_price);
		$criteria->compare('service_name',$this->service_name,true);
		$criteria->compare('service_description',$this->service_description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}