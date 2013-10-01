<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	public function authenticate()
	{
		$providerRecord = Provider::model()->findByAttributes(array('provider_username'=>$this->username));
		
		if($providerRecord===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID; 
		else if(!$providerRecord->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID; 
		else
		{
			$this->_id=$providerRecord->provider_id; 
			$this->setState('name', $providerRecord->provider_name); 
			//user type
			$this->setState('ut','provider');
			
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
	
	public function getId() 
	{
		return $this->_id; 
	}
	
}