<?php

class UserController extends FController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'testLimit' => 0,
            ),
        );
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('register', 'captcha'),
                'users' => array('?'),
            ),
            array('allow', // allow authenticated user to perform 'view' and 'update' actions
                'actions' => array('view', 'update', 'updateMobile', 'updatePassword', 'activate', 'activated', 'resendActivation', 'appointments'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Activate a users account from SMS Code
     * @param string $activationCode the activation code
     */
    public function actionActivate($activationCode = '') {
        $userId = Yii::app()->user->getId();
        $user = $this->loadModel($userId);
        if ($user->user_verif_code == '0')
            $this->redirect('activated');

        $verifAttempts = $user->verifyattemptsLastHour;

        $status = "";
        if ($activationCode && $verifAttempts < 5) {
            $activationCode = strtolower(str_replace(array(' ', "'", "\"", '='), '', $activationCode));

            //check if activation code equals one in db
            if ($user->user_verif_code == $activationCode) {

                //first update model set activation code to '0'
                $user->user_verif_code = '0';
                $user->save(false);

                //update state 'status' to 'active'
                Yii::app()->user->setState('status', 'active');

                //delete verification attempts
                Verifyattempt::model()->deleteAll("user_id=$userId");

                //render activated
            } else {
                $status = "Invalid activation code.";
                $verifyAttempt = new Verifyattempt();
                $verifyAttempt->user_id = $userId;
                $verifyAttempt->verif_code = $activationCode;
                $verifyAttempt->verif_time = new CDbExpression('NOW()');
                $verifyAttempt->save();
            }
        } elseif ($verifAttempts >= 5)
            $status = "You are limited to five attempts per hour, please contact us if you are having difficulties activating your account";

        $this->render('view', array(
            'model' => $user,
            'status' => $status,
        ));
    }

    //shows activated page
    public function actionActivated() {
        $this->render('activated');
    }

    //resends activation code
    public function actionResendActivation() {
        $id = Yii::app()->user->getId();
        $user = $this->loadModel($id);

        if ($user->registrationSmsRecent) {
            
        }
        //if user sent registration SMS in the past 2 minutes, tell them they need to wait 2 minutes after registration
        //if user resent activation sms, they need to wait another hour before resending OR contact us to activate


        if ($user->user_verif_code == '0') {
            $status = "Account already activated";
        } elseif ($user->registrationSmsRecent) {
            $status = "We sent you an sms recently, please wait a few minutes before trying again";
        } elseif ($user->activationSmsRecent) {
            $status = "You are only allowed to resend sms once per hour, please <a href='#'>contact us</a> if you are having issues activating your account";
        } else {
            //resend activation code here of type "Activation"
            $user->sendActivationCode('Activation');

            //Make sure sms is actually sent, and that the validation works where it limits to one per hour.
            //TEST ALL VALIDATIONS

            $status = "Activation code sent.";
        }
        $this->render('view', array(
            'model' => $user,
            'status' => $status,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($status = '') {
        $id = Yii::app()->user->getId();

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'status' => $status,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionRegister() {
        $model = new User('register');

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('site/login', 'u' => $model->user_email, 'p' => $model->user_password));
        }

        $this->render('register', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate() {
        $id = Yii::app()->user->getId();
        $model = $this->loadModel($id);
        $model->scenario = "update";

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->user_id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Updates mobile.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdateMobile() {
        $id = Yii::app()->user->getId();
        $model = $this->loadModel($id);
        $model->scenario = "changeMobile";

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {

            $model->attributes = $_POST['User'];
            $model->user_verif_code = $model->generateCode(5);

            if ($model->save()) {
                $id = Yii::app()->user->getId();
                $user = $this->loadModel($id);
                $status = '';
                if ($_POST['User']['user_mobile_num'] != $model->user_mobile_num) {
                    //if user sent registration SMS in the past 2 minutes, tell them they need to wait 2 minutes after registration
                    //if user resent activation sms, they need to wait another hour before resending OR contact us to activate


                    if ($user->registrationSmsRecent) {
                        $status = "We sent you an sms recently, please wait a few minutes before trying again";
                    } elseif ($user->activationSmsRecent) {
                        $status = "You are only allowed to resend sms once per hour, please <a href='#'>contact us</a> if you are having issues activating your account";
                    } else {
                        //resend activation code here of type "Activation"
                        $user->sendActivationCode('Activation');

                        //Make sure sms is actually sent, and that the validation works where it limits to one per hour.
                        //TEST ALL VALIDATIONS
			Yii::app()->user->setState('status', 'inactive'); 
                        
                        $status = "Activation code sent.";
                        
                    }
                }
                
            }
            $this->redirect('view', array('model' => $model, 'status' => $status));
        }
        $this->render('updatemobile', array(
            'model' => $model,
        ));
    }

    /**
     * Updates mobile.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdatePassword() {
        $id = Yii::app()->user->getId();
        $model = $this->loadModel($id);

        $model->scenario = "changePw";

        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model);

        $status = 'unknown error occurred';
        if (isset($_POST['User'])) {
            if ($_POST['User']['oldpass'] == $model->user_password) {
                if ($_POST['User']['newpass'] == $_POST['User']['confpass']) {
                    if ($_POST['User']['newpass'] == $_POST['User']['oldpass']) {
                        $status = "New password cannot be the same as old password";
                    } else {
                        $model->user_password = $_POST['User']['newpass'];
                        if ($model->save()) {
                            $status = "Password changed successfully";
                        } else {
                            $status = "New password is invalid";
                        }
                    }
                } else {
                    $status = "Confirmation password does not match";
                }
            } else {
                $status = "Old password is invalid";
            }
        }

        $this->render('view', array(
            'model' => $model,
            'status' => $status,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    
    public function actionAppointments(){
        /*
         {
            title: 'Click for Google',
            start: new Date(y, m, 28),
            end: new Date(y, m, 29),
            url: 'google.com'
	 }
         */
        $id = Yii::app()->user->getId();
        $user = $this->loadModel($id);
        $events = '';
        /*$events = "{
            title: 'Click for Google',
            allDay: false,
            editable: false,
            start: '2013-04-09T10:00:00Z',
            end: '2013-04-09T11:00:00Z',
	 }";*/
        foreach ($user->appointments as $appointment){
            if($appointment->appointment_cancelation){
                continue;
            }
            
            $events .= "{
                           title: '". $appointment->service->service_name . "',
                           start: '$appointment->appointment_start_time',
                           end: '$appointment->appointment_end_time',
                           id: $appointment->appointment_id,
                           provider: '" . $appointment->service->provider->provider_name . "',
                           location: '" . $appointment->employee->branch->location->location_name . "',
                           adate: '" . date('d/m/Y', strtotime($appointment->appointment_start_time)) . "',
                           time: '" . date('h:i a', strtotime($appointment->appointment_start_time)) . "',
                           employee: '" . $appointment->employee->employee_name . "',
                           service: '" . $appointment->service->service_name . "',
                        },";
        }
        $this->render('appointments', array(
            'events'=>$events,
        ));
    }

}
