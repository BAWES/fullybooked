<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $user_id
 * @property string $user_email
 * @property string $user_password
 * @property string $user_name
 * @property string $user_gender
 * @property string $user_birth_date
 * @property string $user_mobile_num
 * @property string $user_verif_code
 * @property string $user_account_created
 *
 * The followings are the available model relations:
 * @property Appointment[] $appointments
 * @property Verifyattempt[] $verifyattempts
 */
class User extends CActiveRecord {

    private $salt = "28b206548469ce62182048fd9cf91760";
    public $password_repeat;
    public $verifyCode;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_email, user_password, user_name, user_gender, user_birth_date, user_mobile_num, user_verif_code, user_account_created, password_repeat', 'required', 'on' => array('register',)),
            array('user_name, user_gender, user_birth_date', 'required', 'on' => array('update',)),
            array('password_repeat', 'compare', 'compareAttribute' => 'user_password', 'on' => array('register', 'changePw')),
            array('user_password', 'rehashPassword', 'on' => 'changePw'),
            array('user_email, user_password, user_name', 'length', 'max' => 100),
            array('user_mobile_num', 'length', 'max' => 8, 'min' => 8),
            array('user_verif_code', 'length', 'max' => 20),
            array('user_gender', 'in', 'range' => array('male', 'female')),
            array('user_email', 'email'),
            array('user_email', 'unique', 'message' => "There is already an account with that email"),
            array('user_mobile_num', 'unique', 'message' => "There is already an account with that mobile number",
                'criteria' => array(
                    'condition' => 'user_verif_code=0'
                )
            ),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register'),
            array('user_mobile_num', 'numerical', 'integerOnly' => true),
            array('user_birth_date', 'date', 'format' => 'yyyy-MM-dd'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, user_email, user_password, user_name, user_gender, user_birth_date, user_mobile_num, user_verif_code, user_account_created', 'safe', 'on' => 'search'),
        );
    }

    public function rehashPassword($attribute, $params) {
        $this->user_password = $this->hashPassword($this->user_password, $this->salt);
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'appointments' => array(self::HAS_MANY, 'Appointment', 'user_id', 'order' => 'appointment_start_time ASC', 'with' => array('service', 'employee')),
            'sms' => array(self::HAS_MANY, 'Sms', 'user_id', 'order' => 'sms_time DESC'),
            'registrationSmsRecent' => array(self::STAT, 'Sms', 'user_id', 'condition' => "sms_time>DATE_ADD(NOW(), INTERVAL -3 minute) AND sms_type='Registration'"),
            'activationSmsRecent' => array(self::STAT, 'Sms', 'user_id', 'condition' => "sms_time>DATE_ADD(NOW(), INTERVAL -1 hour) AND sms_type='Activation'"),
            'verifyattempts' => array(self::HAS_MANY, 'Verifyattempt', 'user_id'),
            'verifyattemptsLastHour' => array(self::STAT, 'Verifyattempt', 'user_id', 'condition' => "verif_time>DATE_ADD(NOW(), INTERVAL -1 hour)"),
                //add relation to return number of attempts this hour
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => 'User',
            'user_email' => 'Email',
            'user_password' => 'Password',
            'password_repeat' => 'Confirm Password',
            'user_name' => 'Full Name',
            'user_gender' => 'Gender',
            'user_birth_date' => 'Birth Date',
            'user_mobile_num' => 'Mobile Number',
            'user_verif_code' => 'Verification Code',
            'user_account_created' => 'Date Created',
            'verifyCode' => 'Verification Code',
        );
    }

    public function sendActivationCode($smsType = "") {
        $phone = $this->user_mobile_num;
        $activationCode = strtoupper($this->user_verif_code);

        $sms = new Sms();
        $sms->user_id = $this->user_id;
        $sms->sms_type = $smsType;
        $sms->sms_phone = $phone;
        $sms->sms_message = "Thanks for signing up with Fullybookedkw.com%0a%0aYour Activation Code: $activationCode";
        $sms->save();
    }

    public function generateCode($characters) {
        $possible = "23456789bcdfghjkmnpqrtvwxyz";
        $code = '';
        $i = 0;
        for ($i = 0; $i < $characters; $i++) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        }
        return $code;
    }

    protected function beforeValidate() {
        if ($this->isNewRecord) {
            $this->user_verif_code = $this->generateCode(5);
            $this->user_account_created = new CDbExpression('NOW()');
        }

        return parent::beforeValidate();
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->user_password = $this->hashPassword($this->user_password, $this->salt);
            }
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $this->sendActivationCode('Registration');
        }
    }

    //checks password param if equals to current users password
    public function validatePassword($password, $isEncrypted) {
        //echo $password . ' ' . $isEncrypted;
        if (!$isEncrypted) {
            //echo 'not encrypted';
            return $this->hashPassword($password, $this->salt) === $this->user_password;
        } else {
            //echo 'encrypted';
            return $password === $this->user_password;
        }
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

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('user_email', $this->user_email, true);
        $criteria->compare('user_password', $this->user_password, true);
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('user_gender', $this->user_gender, true);
        $criteria->compare('user_birth_date', $this->user_birth_date, true);
        $criteria->compare('user_mobile_num', $this->user_mobile_num, true);
        $criteria->compare('user_verif_code', $this->user_verif_code, true);
        $criteria->compare('user_account_created', $this->user_verif_code, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}