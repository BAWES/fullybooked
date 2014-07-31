<?php

/**
 * CWebApplication overridden to add functionality
 */
class MyWebApplication extends CWebApplication {

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            $route = Yii::app()->request->requestUri;
            $userType = $userId = "";

            $browser = $_SERVER['HTTP_USER_AGENT'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            //If user is logged in to the front end, log his info
            if (isset(Yii::app()->user->ut)) {
                if (Yii::app()->user->ut == 'user') {
                    $userType = "User";
                    $userId = Yii::app()->user->getId();
                }
            } else {//Else, give him unique guest ID session to log his movements
                if (!isset(Yii::app()->user->guestId)) {
                    Yii::app()->user->setState('guestId', time() . rand());
                }

                $userType = "Guest";
                $userId = Yii::app()->user->guestId;
            }

            //Now insert logged info
            $activity = new Useractivity();
            $activity->activity_user_type = $userType;
            $activity->activity_user_id = $userId;
            $activity->activity_user_ip = $ipAddress;
            $activity->activity_user_browser = $browser;
            $activity->activity_route = $route;
            $activity->activity_datetime = new CDbExpression('NOW()');
            $activity->save();


            return true;
        }
        else
            return false;
    }

}