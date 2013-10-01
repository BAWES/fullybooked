<?php

/**
 * This is the model class for table "branch".
 *
 * The followings are the available columns in table 'branch':
 * @property string $branch_id
 * @property string $provider_id
 * @property string $location_id
 * @property string $branch_address
 * @property string $branch_phone
 *
 * The followings are the available model relations:
 * @property Provider $provider
 * @property Location $location
 * @property Employee[] $employees
 */
class Branch extends CActiveRecord
{
	public $location_search;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Branch the static model class
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
		return 'branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('provider_id, location_id, branch_address, branch_phone', 'required'),
			array('provider_id, location_id', 'length', 'max'=>20),
			array('branch_phone', 'length', 'max'=>100),
			array('provider_id', 'validateMaximumBranches', 'on'=>'create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('branch_id, provider_id, location_id, branch_address, branch_phone, location_search', 'safe', 'on'=>'search'),
		);
	}
	
	public function validateMaximumBranches($attribute,$params)
	{
		$currentProvider = Provider::model()->find('provider_id = '.$this->provider_id);
		$currentBranches = $currentProvider->totalBranches;
		$maximumBranches = $currentProvider->provider_maximum_branches;
		if($currentBranches>=$maximumBranches)
	    	$this->addError('provider','You have reached the maximum allowed branches, please contact the admin if you need to add more branches. ');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'provider' => array(self::BELONGS_TO, 'Provider', 'provider_id'),
			'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
			'employees' => array(self::HAS_MANY, 'Employee', 'branch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'branch_id' => 'Branch',
			'location_search' => 'Location',
			'provider_id' => 'Provider',
			'location_id' => 'Location',
			'branch_address' => 'Branch Address',
			'branch_phone' => 'Branch Phone',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($condition = "")
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->condition = $condition;
		$criteria->with = array("location");

		$criteria->compare('branch_id',$this->branch_id,true);
		$criteria->compare('provider_id',$this->provider_id,true);
		//$criteria->compare('location_id',$this->location_id,true);
		
		//Add search function to related
		$criteria->compare('location.location_name',$this->location_search,true);
		
		$criteria->compare('branch_address',$this->branch_address,true);
		$criteria->compare('branch_phone',$this->branch_phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array( //add sorting to related model
				'attributes'=>array(
					'location_search'=>array(
						'asc'=>'location.location_name',
						'desc'=>'location.location_name DESC',
					),
					'*', //* means treat other fields normally
				),
			),
		));
	}
}