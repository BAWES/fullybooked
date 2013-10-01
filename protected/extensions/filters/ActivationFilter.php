<?php
//ActivationFilter checks whether an account is activated, if not activated redirect to activation page
/*
* You need to pass the following:
* $status -> Variable holding the result (whether active or inactive)
* $inactiveValue -> The string that represents an "inactive" account
* $redirect -> The Module/Controller/Action string that should be redirected to if inactive
*/

class ActivationFilter extends CFilter 
{
	public $status, $activeValue, $inactiveValue, $redirect;
	
	protected function preFilter($filterChain)
	{
		if($this->inactiveValue == $this->status){
			Yii::app()->controller->redirect(array($this->redirect));
			return false;
		}
		
		
	    // logic being applied before the action is executed
		return true; // false if the action should not be executed 
	}
	
	protected function postFilter($filterChain)
	{
        // logic being applied after the action is executed
	}
}