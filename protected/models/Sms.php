<?php

/**
 * This is the model class for table "sms".
 *
 * The followings are the available columns in table 'sms':
 * @property string $sms_id
 * @property string $user_id
 * @property string $sms_type
 * @property string $sms_phone
 * @property string $sms_message
 * @property string $sms_response
 * @property string $sms_time
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Sms extends CActiveRecord
{
	private $username = "danasms";
	private $password = "fbookedsms";
	private $sender = "Fullybooked";
	
	private $areaCode = "965";
	private $language = "1";
		//1 - English
		//2 - Arabic (Windows)
		//3 - Arabic (UTF-8)
		//4 - Unicode
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sms the static model class
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
		return 'sms';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, sms_type, sms_phone, sms_message', 'required'),
			array('user_id, sms_type, sms_phone', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('sms_id, user_id, sms_type, sms_phone, sms_message, sms_response, sms_time', 'safe', 'on'=>'search'),
		);
	}
	
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord){
				$this->sms_time = new CDbExpression('NOW()');
				$this->sms_phone = $this->areaCode.$this->sms_phone;
				
				//Message: Replace all spaces with "+" and replace all \n with "%0a"
				$this->sms_message = str_replace(' ','+',$this->sms_message);
				
				$webrequest = "http://www.kwtsms.com/API/send/?username=".$this->username.
								"&password=".$this->password."&sender=".$this->sender.
								"&mobile=".$this->sms_phone."&lang=".$this->language."&message=".$this->sms_message;
				$ch = curl_init($webrequest);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				$data = curl_exec($ch);
				curl_close($ch);
				
				$this->sms_response = $data;
			}
			return true;
		}
		else return false;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sms_id' => 'Sms',
			'user_id' => 'User',
			'sms_type' => 'Sms Type',
			'sms_phone' => 'Sms Phone',
			'sms_message' => 'Sms Message',
			'sms_response' => 'Sms Response',
			'sms_time' => 'Sms Time',
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

		$criteria->compare('sms_id',$this->sms_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('sms_type',$this->sms_type,true);
		$criteria->compare('sms_phone',$this->sms_phone,true);
		$criteria->compare('sms_message',$this->sms_message,true);
		$criteria->compare('sms_response',$this->sms_response,true);
		$criteria->compare('sms_time',$this->sms_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}