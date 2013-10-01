<?php

class EmployeeController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'column2';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Employee('create');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Employee'])) {
            $model->attributes = $_POST['Employee'];


            if ($model->validate()) {

                if ($model->save(false)) {
                    $this->redirect(array('view', 'id' => $model->employee_id));
                }
            }
        }

        //generate dropdown list
        $dropdown = CHtml::listData(Branch::model()->findAll('provider_id=' . Yii::app()->user->id), 'branch_id', 'branch_address');

        $daysDropdown = array(
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
        );

        //generate list of services offered by the provider
        $allServices = CHtml::listData(Service::model()->findAll('provider_id=' . Yii::app()->user->id), 'service_id', 'service_name');

        $this->render('create', array(
            'model' => $model,
            'dropdown' => $dropdown,
            'daysDropdown' => $daysDropdown,
            'allServices' => $allServices,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Employee'])) {
            $model->attributes = $_POST['Employee'];


            if ($model->validate()) {
                if ($model->save(false)) {
                    $this->redirect(array('view', 'id' => $model->employee_id));
                }
            }
        }

        //generate dropdown list
        $dropdown = CHtml::listData(Branch::model()->findAll('provider_id=' . Yii::app()->user->id), 'branch_id', 'branch_address');

        $daysDropdown = array(
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
        );

        //generate list of services offered by the provider
        $allServices = CHtml::listData(Service::model()->findAll('provider_id=' . Yii::app()->user->id), 'service_id', 'service_name');

        $this->render('update', array(
            'model' => $model,
            'dropdown' => $dropdown,
            'daysDropdown' => $daysDropdown,
            'allServices' => $allServices,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Employee', array(
                    'criteria' => array(
                        'with' => array(
                            'branch',
                            'provider' => array(
                                'select' => false,
                                'condition' => "provider.provider_id=" . Yii::app()->user->id,
                            )
                        )
                    )
                ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Employee('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Employee']))
            $model->attributes = $_GET['Employee'];

        $this->render('admin', array(
            'model' => $model,
            'providerID' => Yii::app()->user->id,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        //Only select employees that belong to the provider
        $model = Employee::model()->with(array(
                    'provider' => array(
                        'select' => false,
                        'condition' => "provider.provider_id=" . Yii::app()->user->id,
                    ),
                ))->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'employee-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
