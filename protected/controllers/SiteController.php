<?php

class SiteController extends FController {

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
            array('deny',
                'actions' => array('login'),
                'users' => array('@'),
            ),
        );
    }

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
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $industries = Category::model()->findAll();
        $locations = Location::model()->findAll();
        $dates = array();
        for ($i = 0; $i < 10; $i++) {
            $dates[] = date("d-m-Y", strtotime("+$i day"));
        }
        $times = array();

        for ($i = 8; $i < 22; $i++) {
            $times[] = date("h:iA", strtotime("$i:00")) . " - " . date("h:iA", strtotime(($i + 1) . ":00"));
        }
        $user = User::model()->findByPk(Yii::app()->user->getId());

        $days = array();
        $daysCount = 0;
        if ($user) {
            $appointments = $user->appointments;


            //To-do: Make sure to only start counting appointments after today's date
            if (count($appointments) != 0) {
                foreach ($appointments as $appointment) {
                    if (date('Y-m-d H.i', strtotime($appointment->appointment_start_time)) < date('Y-m-d H.i')) {
                        continue;
                    }
                    if($appointment->appointment_cancelation){
                        continue;
                    }
                    if ($daysCount >= 3) {
                        break;
                    }
                    $day = date('d/m/Y', strtotime($appointment->appointment_start_time));
                    if (!isset($days[$day])) {
                        $days[$day] = array();
                        $daysCount++;
                    }
                    $days[$day][] = $appointment;
                }
            }
        }
        $this->render('index', array(
            'industries' => $industries,
            'locations' => $locations,
            'dates' => $dates,
            'times' => $times,
            'days' => $days,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin($u = '', $p = '') {

        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        } elseif ($u && $p) {
            $model->username = $u;
            $model->password = $p;
            $model->isEncrypted = true;

            if ($model->validate() && $model->login()) {
                $this->redirect(array("user/view"));
            } else {
                print_r($model->errors);
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}