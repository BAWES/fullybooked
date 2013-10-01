<?php

/**
 * This is the model class for table "useractivity".
 *
 * The followings are the available columns in table 'useractivity':
 * @property string $activity_id
 * @property string $activity_user_type
 * @property string $activity_user_id
 * @property string $activity_user_ip
 * @property string $activity_user_browser
 * @property string $activity_route
 * @property string $activity_datetime
 */
class Useractivity extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Useractivity the static model class
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
		return 'useractivity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activity_user_type, activity_user_id, activity_user_ip, activity_user_browser, activity_route, activity_datetime', 'required'),
			array('activity_user_type', 'length', 'max'=>12),
			array('activity_user_id', 'length', 'max'=>20),
			array('activity_user_ip, activity_route', 'length', 'max'=>100),
			array('activity_user_browser', 'length', 'max'=>120),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('activity_id, activity_user_type, activity_user_id, activity_user_ip, activity_user_browser, activity_route, activity_datetime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'activity_id' => 'Activity',
			'activity_user_type' => 'Activity User Type',
			'activity_user_id' => 'Activity User',
			'activity_user_ip' => 'Activity User Ip',
			'activity_user_browser' => 'Activity User Browser',
			'activity_route' => 'Activity Route',
			'activity_datetime' => 'Activity Datetime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('activity_id',$this->activity_id,true);
		$criteria->compare('activity_user_type',$this->activity_user_type,true);
		$criteria->compare('activity_user_id',$this->activity_user_id,true);
		$criteria->compare('activity_user_ip',$this->activity_user_ip,true);
		$criteria->compare('activity_user_browser',$this->activity_user_browser,true);
		$criteria->compare('activity_route',$this->activity_route,true);
		$criteria->compare('activity_datetime',$this->activity_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}