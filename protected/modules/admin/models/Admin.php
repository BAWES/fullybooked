<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property string $admin_id
 * @property string $admin_name
 * @property string $admin_email
 * @property string $admin_username
 * @property string $admin_password
 */
class Admin extends CActiveRecord
{

	private $salt="28b206548469ce62182048fd9cf91760";
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Admin the static model class
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
		return 'admin';
	}	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('admin_name, admin_email, admin_username, admin_password', 'required'),
			array('admin_password','rehashPassword','on'=>'changePw'),
			array('admin_name, admin_username', 'length', 'max'=>50),
			array('admin_email', 'length', 'max'=>64),
			array('admin_email','email'),
			array('admin_password', 'length', 'max'=>80),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('admin_id, admin_name, admin_email, admin_username, admin_password', 'safe', 'on'=>'search'),
		);
	}

	public function rehashPassword($attribute,$params){
		$this->admin_password = $this->hashPassword($this->admin_password, $this->salt);
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
			'admin_id' => 'ID',
			'admin_name' => 'Admin Name',
			'admin_email' => 'Admin Email',
			'admin_username' => 'Admin Username',
			'admin_password' => 'Admin Password',
		);
	}
	
	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord) $this->admin_password = $this->hashPassword($this->admin_password, $this->salt);
			
			return true;
		}
		else
			return false;
	}
	
	//checks password param if equals to current users password
	public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->admin_password;
    }
 
 	//hashes password input using given salt
    public function hashPassword($password,$salt)
    {
        return md5($salt.$password);
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

		$criteria->compare('admin_id',$this->admin_id,true);
		$criteria->compare('admin_name',$this->admin_name,true);
		$criteria->compare('admin_email',$this->admin_email,true);
		$criteria->compare('admin_username',$this->admin_username,true);
		$criteria->compare('admin_password',$this->admin_password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}