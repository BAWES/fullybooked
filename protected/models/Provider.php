<?php

/**
 * This is the model class for table "provider".
 *
 * The followings are the available columns in table 'provider':
 * @property string $provider_id
 * @property string $category_id
 * @property string $provider_logo
 * @property string $provider_name
 * @property string $provider_username
 * @property string $provider_password
 * @property string $provider_booking_startdate
 * @property string $provider_booking_enddate
 * @property string $provider_contact_name
 * @property string $provider_contact_number
 * @property integer $provider_maximum_branches
 *
 * The followings are the available model relations:
 * @property Branch[] $branches
 * @property Category $category
 * @property Service[] $services
 */
class Provider extends CActiveRecord {

    public $category_search;
    private $salt = "28b206548469ce62182048fd9cf91760";
    public $bookingStart, $bookingEnd; //different date format

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Salon the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function afterFind() {
        // convert to display format
        $this->bookingStart = date('M j, Y', strtotime($this->provider_booking_startdate));
        $this->bookingEnd = date('M j, Y', strtotime($this->provider_booking_enddate));

        parent::afterFind();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'provider';
    }

    //returns true if Salon is allowed booking, false if not
    public function checkBookingAllowed() {
        $todaysDate = strtotime(date('Y-m-d'));
        $bookingStart = strtotime($this->provider_booking_startdate);
        $bookingEnd = strtotime($this->provider_booking_enddate);

        if (($todaysDate >= $bookingStart) && ($todaysDate < $bookingEnd)) {
            return true;
        }return false;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('provider_name, provider_maximum_branches , provider_username, provider_password, provider_booking_startdate, provider_booking_enddate, provider_contact_name, provider_contact_number', 'required'),
            array('provider_password', 'rehashPassword', 'on' => 'changePw'),
            array('provider_logo', 'file', 'types' => 'jpg, gif, png, jpeg', 'allowEmpty' => false, 'on' => 'create'),
            array('provider_logo', 'file', 'types' => 'jpg, gif, png, jpeg', 'allowEmpty' => true, 'on' => 'update'),
            array('provider_name, provider_username', 'length', 'max' => 128),
            array('provider_password, provider_contact_number', 'length', 'max' => 64),
            array('provider_contact_name', 'length', 'max' => 100),
            array('provider_booking_startdate, provider_booking_enddate', 'date', 'format' => 'yyyy-MM-dd'),
            array('provider_booking_startdate', 'compare', 'compareAttribute' => 'provider_booking_enddate', 'operator' => '<',
                'allowEmpty' => false, 'message' => 'Booking start date cant be after the end date.'),
            array('provider_maximum_branches', 'numerical', 'integerOnly' => true),
            array('category_id', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('provider_id, category_search, category_id, provider_name, provider_username, provider_password, provider_booking_startdate, provider_booking_enddate, provider_contact_name, provider_contact_number, provider_maximum_branches', 'safe', 'on' => 'search'),
        );
    }

    public function rehashPassword($attribute, $params) {
        $this->provider_password = $this->hashPassword($this->provider_password, $this->salt);
    }

    //return logo image url
    public function getLogoLarge() {
        return Yii::app()->request->baseUrl . "/images/provider/logo/" . $this->provider_logo;
    }

    //return logo thumb image url
    public function getLogoThumb() {
        return Yii::app()->request->baseUrl . "/images/provider/thumb/" . $this->provider_logo;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'branches' => array(self::HAS_MANY, 'Branch', 'provider_id', 'with' => 'location'),
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'services' => array(self::HAS_MANY, 'Service', 'provider_id'),
            'employees' => array(self::HAS_MANY, 'Employee', array('branch_id' => 'branch_id'), 'through' => 'branches'),
            'totalBranches' => array(self::STAT, 'Branch', 'provider_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'provider_id' => 'ID',
            'category_id' => 'Category',
            'provider_logo' => 'Logo',
            'provider_name' => 'Name',
            'provider_username' => 'Username',
            'provider_password' => 'Password',
            'provider_booking_startdate' => 'Booking Startdate',
            'provider_booking_enddate' => 'Booking Enddate',
            'provider_contact_name' => 'Contact Name',
            'provider_contact_number' => 'Contact Number',
            'provider_maximum_branches' => 'Maximum Branches',
            'provider_maximum_branches' => 'Provider Maximum Branches',
            'category_search' => 'Category',
        );
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord)
                $this->provider_password = $this->hashPassword($this->provider_password, $this->salt);

            return true;
        }
        else
            return false;
    }

    protected function beforeDelete() {
        if (parent::beforeDelete()) {
            $oldPic = $this->provider_logo;
            if (!empty($oldPic)) {
                $oldPic1 = Yii::app()->basePath . "/../images/provider/logo/" . $oldPic;
                $oldPic2 = Yii::app()->basePath . "/../images/provider/thumb/" . $oldPic;

                if (file_exists($oldPic1))
                    unlink($oldPic1);
                if (file_exists($oldPic2))
                    unlink($oldPic2);
            }

            return true;
        }
        else
            return false;
    }

    //checks password param if equals to current users password
    public function validatePassword($password) {
        return $this->hashPassword($password, $this->salt) === $this->provider_password;
    }

    //hashes password input using given salt
    public function hashPassword($password, $salt) {
        return md5($salt . $password);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->with = "category";

        $criteria->compare('provider_id', $this->provider_id, true);
        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('provider_logo', $this->provider_logo, true);
        $criteria->compare('provider_name', $this->provider_name, true);
        $criteria->compare('provider_username', $this->provider_username, true);
        $criteria->compare('provider_password', $this->provider_password, true);
        $criteria->compare('provider_booking_startdate', $this->provider_booking_startdate, true);
        $criteria->compare('provider_booking_enddate', $this->provider_booking_enddate, true);
        $criteria->compare('provider_contact_name', $this->provider_contact_name, true);
        $criteria->compare('provider_contact_number', $this->provider_contact_number, true);
        $criteria->compare('provider_maximum_branches', $this->provider_maximum_branches);

        //Add search function to related
        $criteria->compare('category.category_name', $this->category_search, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(//add sorting to related model
                'attributes' => array(
                    'category_search' => array(
                        'asc' => 'category.category_name',
                        'desc' => 'category.category_name DESC',
                    ),
                    '*', //* means treat other fields normally
                ),
            ),
        ));
    }

}