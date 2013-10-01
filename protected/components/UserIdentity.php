<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
        public $isEncrypted = false;
	
	public function authenticate()
	{
            echo $this->isEncrypted;
		$userRecord = User::model()->findByAttributes(array('user_email'=>CHtml::encode($this->username)));
		if($userRecord===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID; 
		else if(!$userRecord->validatePassword($this->password, $this->isEncrypted))
			$this->errorCode=self::ERROR_PASSWORD_INVALID; 
		else
		{
			$this->_id=$userRecord->user_id; 
			$this->setState('name', $userRecord->user_name); 
			// "ac" for active / "ic" for inactive
			if($userRecord->user_verif_code == '0') $this->setState('status', 'active'); 
			else $this->setState('status', 'inactive'); 
			
			//user type
			$this->setState('ut','user');
			
			//Update logs, this guest is now a user
			$guestId = Yii::app()->user->guestId;			
			Useractivity::model()->updateAll(array("activity_user_type"=>'User', 'activity_user_id'=>$userRecord->user_id),
										"activity_user_type='Guest' AND activity_user_id='$guestId'");
			
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	
	public function getId() 
	{
		return $this->_id; 
	}
}