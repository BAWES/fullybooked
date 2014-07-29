<?php

class ProviderController extends FController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        //set return url
        Yii::app()->user->setReturnUrl(array('provider/view', 'id' => $id));
        $model = $this->loadModel($id);
        if (date('Y-m-d', strtotime($model->provider_booking_startdate)) > date('Y-m-d')) {
            $this->render('show', array(
                'model' => $model,
            ));
        } elseif (date('Y-m-d', strtotime($model->provider_booking_enddate)) < date('Y-m-d')) {
            $this->render('show', array(
                'model' => $model,
            ));
        } else {
            $this->render('view', array(
                'model' => $model,
            ));
        }
    }

    public function actionTimeTable($branchID, $serviceID, $_date = '') {
        if (!Yii::app()->user->isGuest) {
            $branchModel = Branch::model()->findByPk($branchID);
            $employees = array();
            foreach ($branchModel->employees as $employee) {
                $added = false;
                foreach ($employee->services as $service) {
                    if ($service->service_id == $serviceID) {
                        $employees[] = $employee;
                        $added = true;
                    }
                    if ($added) {
                        break;
                    }
                }
            }
            
            if ($_date == '') {
                $day = date('l');
                $date = date('d/m/Y');
            } else {
                $day = date('l', strtotime($_date));
                $date = date('d/m/Y', strtotime($_date));
            }
            $output = '';

            foreach ($employees as $employee) {
                if ($employee->employee_dayoff == $day) {
                    continue;
                } else {
                    $output .= "<div><h3 name=$employee->employee_id>$employee->employee_name</h3>";

                    $time = strtotime($employee->employee_workstart);
                    $time = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $time));

                    $time_end = $time + 60 * $service->service_duration;
                    $end = strtotime($employee->employee_workend);
                    $end = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $end));
                    $breakstart = strtotime($employee->employee_breakstart);
                    $breakstart = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $breakstart));
                    $breakend = strtotime($employee->employee_breakend);
                    $breakend = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $breakend));
                    $time_start = strtotime($employee->employee_workstart);
                    $time_start = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $time_start));
//                echo "Start time: " . date('Y-m-d H.i', $time) . '</br>';
//                echo "End of service time: " . date('Y-m-d H.i', $time_end) . '</br>';
//                echo "End time: " . date('Y-m-d H.i', $end) . '</br>';
//                echo "Break start time: " . date('Y-m-d H.i', $breakstart) . '</br>';
//                echo "Break end time: " . date('Y-m-d H.i', $breakend) . '</br>';
                    while ($time < $end) {
                        $availability = true;
                        if (!(($time <= $breakstart
                                && $time_end <= $breakstart)
                                ||
                                ($time >= $breakend
                                && $time_end >= $breakend))) {
                            //Checks that the timings are not during his break;
                            $availability = false;
                        } elseif (!($time >= $time_start
                                && $time_end <= $end)) {
                            //Checks that the timings are within his working hours

                            $availability = false;
                        } else {
                            foreach ($employee->appointments as $appointment) {
                                if (!$appointment->appointment_cancelation) {
                                    if (!(($time <= strtotime($appointment->appointment_start_time)
                                            && $time_end <= strtotime($appointment->appointment_start_time))
                                            ||
                                            ($time >= strtotime($appointment->appointment_end_time)
                                            && $time_end >= strtotime($appointment->appointment_end_time)))) {
                                        //Checks that the timings are not during another appointment
                                        $availability = false;
                                    }
                                }
                            }
                        }
                        if ($availability) {
                            $availability = '';
                        } else {
                            $availability = "class='unavailable'";
                        }
                        $output .= "<a href='#' $availability>" . date("H.i", $time) . "</a>";

                        $time += 60 * 60 * 0.5;
                        $time_end = $time + 60 * $service->service_duration;
                    }
                    $output .= "<br class='clear'/></div>";
                }
            }
            if ($output == '') {
                $output = 'No appointments available on this date';
            }
            $this->renderPartial('_timetable', array(
                'day' => $day,
                'branchModel' => $branchModel,
                'employees' => $employees,
                'date' => $date,
                'output' => $output,
            ));
        }
    }

    public function actionServices($id) {
        $branch = Branch::model()->findByPk($id);

        $this->renderPartial('_services', array(
            'branch' => $branch,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex($category = null, $location = null) {
        //set return url
        Yii::app()->user->setReturnUrl(array('provider/index'));

        $criteria = new CDbCriteria();
        $criteria->order = "provider_name ASC";
        if ($category)
            $criteria->condition = "category_id=" . $category;
        if ($location) {
            $model = Location::model()->findByPk($location)->providers;
        } else {
            $model = Provider::model()->findAll($criteria);
        }

        //category list
        $criteria = new CDbCriteria();
        $criteria->order = "category_name";
        $categories = Category::model()->findAll($criteria);
        $allLetters = array('#', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $lettersUsed = array();
        $tempLetter = null;
        $locations = Location::model()->findAll();

//salon list output
        $output = "";
        foreach ($model as $provider) {
            if ($location) {
                if ($category && $provider->category_id != $category) {
                    continue;
                }
            }
            $firstLetter = strtoupper(substr($provider->provider_name, 0, 1));
            if ($tempLetter != $firstLetter) {
                //if isNumber -> put into the # category
                if (is_numeric($firstLetter)) {
                    $lettersUsed[] = $tempLetter = "#";
                }else
                    $lettersUsed[] = $tempLetter = $firstLetter;

                if ($output == "") {
                    if (is_numeric($firstLetter)) {
                        $output .= "<li id='num'><p>#</p><ul>";
                    }else
                        $output .= "<li id='$firstLetter'><p>$firstLetter</p><ul>";
                } else {
                    $output .= "</ul></li><li id='$firstLetter'><p>$firstLetter</p><ul>";
                }
            }
            $available = '';
            if (date('Y-m-d', strtotime($provider->provider_booking_enddate)) >= date('Y-m-d') &&
                    date('Y-m-d', strtotime($provider->provider_booking_startdate)) <= date('Y-m-d')) {
                $available = "class='available'";
            }
            $output .= "<li><a href='" . $this->createUrl('provider/view', array('id' => $provider->provider_id)) . "' $available>" . $provider->provider_name . "</a></li>";
        }

//letter navigation output
        $letterNavigation = "";
        foreach ($allLetters as $letter) {
            if (in_array($letter, $lettersUsed)) {
                if ($letter == "#")
                    $letterNavigation .= "<li><a href='#num'>$letter</a></li>";
                else
                    $letterNavigation .= "<li><a href='#$letter'>$letter</a></li>";
            }else
                $letterNavigation .= "<li>$letter</li> ";
        }

//categories output generated
        $categoryNavigation = "";
        foreach ($categories AS $categoryOption) {
            if ($category == $categoryOption->category_id) {
                $categoryNavigation .= "<option value=\"$categoryOption->category_id\" selected='selected'>$categoryOption->category_name</option>";
            } else {
                $categoryNavigation .= "<option value=\"$categoryOption->category_id\">$categoryOption->category_name</option>";
            }
        }

//location navigation output
        $locationNavigation = "";
        foreach ($locations as $locationOption) {
            if ($location == $locationOption->location_id) {
                $locationNavigation .= "<option value=\"$locationOption->location_id\" selected='selected'>$locationOption->location_name</option>";
            } else {
                $locationNavigation .= "<option value=\"$locationOption->location_id\">$locationOption->location_name</option>";
            }
        }
        $this->render('index', array(
            'model' => $model,
            'categories' => $categories,
            'categoryNavigation' => $categoryNavigation,
            'letterNavigation' => $letterNavigation,
            'output' => $output,
            'locationNavigation' => $locationNavigation,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Provider::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'provider-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
