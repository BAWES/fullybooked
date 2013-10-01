<?php

class DefaultController extends Controller {

    public function actionIndex($id = null, $date = null) {
        $model = Provider::model()->findByPk(Yii::app()->user->id);
        $menuArray = array();
        foreach ($model->branches as $branch) {
            $menuArray[] = array(
                'label' => $branch->location->location_name,
                'url' => array("default/index", 'id' => $branch->branch_id));
        }
        $branch = null;
        $appointmentList = null;
        if ($id) {
            $branch = Branch::model()->findByPk($id);
            $today = date('Y-m-d');
            $employeesIds = '0';
            foreach ($branch->employees as $employee) {
                $employeesIds .= ',' . $employee->employee_id;
            }
//            $employeesIds = substr($employeesIds, 0, strlen($employeesIds)-1);
            $criteria = new CDbCriteria();
            $criteria->condition = "employee_id IN ($employeesIds) AND appointment_start_time >= '$today' AND appointment_cancelation IS NULL ORDER BY appointment_start_time ASC";
            $appointments = Appointment::model()->findAll($criteria);
            $appointmentList = array();
            foreach ($appointments as $appointment) {
                $date = date('Y-m-d', strtotime($appointment->appointment_start_time));
                if (!isset($appointmentList["$date"])) {
                    $appointmentList["$date"] = array();
                }
                $appointmentList["$date"][] = $appointment;
            }
        }

        $this->render('index', array(
            'model' => $model,
            'menuArray' => $menuArray,
            'branch' => $branch,
            'appointmentList' => $appointmentList,
        ));
    }

    public function actionAddAppointment($id = null) {
        $branches = Provider::model()->findByPk(Yii::app()->user->id)->branches;
        $model = $id ? Branch::model()->findByPk($id) : $branches[0];
        $services = array();
        foreach ($model->employees as $employee) {
            foreach ($employee->services as $service) {
                if (!isset($services[$service->service_id])) {
                    $services[$service->service_id] = $service;
                }
            }
        }
        $this->render('addAppointment', array(
            'model' => $model,
            'branches' => $branches,
            'services' => $services,
        ));
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin() {
        if (Yii::app()->user->isGuest) {
            $this->layout = "notlogged";
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
                    $this->redirect(Yii::app()->getModule('manager')->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
        else
            $this->redirect(array('index'));
    }

    public function actionLogout() {
        Yii::app()->user->logout(false);
        $this->redirect(Yii::app()->getModule('manager')->user->loginUrl);
    }

    public function actionCancelAppointment() {
        if (isset($_POST['id']) && isset($_POST['status'])) {
            $id = (int) $_POST['id'];
            $status = $_POST['status'];
            if ($status == 'absent' || $status == 'user' || $status == 'provider') {
                $appointment = Appointment::model()->findByPk($id);
                $appointment->cancelAppointment($status);
            } else {
                echo "Invalid status";
            }
        } else {
            echo 'No data found';
        }
    }

    public function actionBook() {
//        $user = User::model()->findByPk(Yii::app()->user->id);
        if (isset($_POST)) {
            $employee = Employee::model()->findByPk($_POST['employee_id']);
            $service = Service::model()->findByPk($_POST['service_id']);
            $date = strtotime($_POST['date']);
            $date = date('Y-m-d', $date);
            $starttime = strtotime($date . ' ' . $_POST['starttime']);

            $user_phone = $_POST['user_phone'];

            $user = User::model()->find("user_mobile_num = $user_phone");
            if ($user) {
                $user_name = $user->user_name;
                $user_id = $user->user_id;
            } else {
                $user_name = $_POST['user_name'];
                $user_id = 0;
            }

            if (strlen($user_phone) != 8) {
                echo 'Error: Invalid phone number';
            } elseif ($user_name == '') {
                echo 'Error: Invalid name';
            } elseif (date('Y-m-d', strtotime($employee->branch->provider->provider_booking_startdate)) > date('Y-m-d', $starttime)) {
                echo 'Error: Booking for this provider hasn\'t started yet';
            } elseif (date('Y-m-d', strtotime($employee->branch->provider->provider_booking_enddate)) < date('Y-m-d', $starttime)) {
                echo 'Error: Booking for this provider has already ended';
            } else {
//            echo "Employee: $employee->employee_id, User: $user->user_id, Service: $service->service_id, Start: $starttime";
                $appointment = new Appointment();
                $appointment->user_name = $user_name;
                $appointment->employee_id = $employee->employee_id;
                $appointment->service_id = $service->service_id;
                $appointment->user_id = $user_id;
                $appointment->user_phone = $user_phone;
                $appointment->appointment_start_time = date('Y-m-d H:i', $starttime);
                $appointment->appointment_end_time = date('Y-m-d H:i', $starttime + 60 * $service->service_duration);
                $appointment->appointment_source = 'provider';

                if ($appointment->save()) {
                    echo "
                <p>
                <span class='ui-icon ui-icon-circle-check' style='float: left; margin: 0 7px 50px 0;'></span>
                Your appointment is confirmed.
                </p>
                <p>
                    <b>Appointment ID - $appointment->appointment_id</b>
                </p>";
                } else {
//                echo 'Error saving appointment';
                }
            }
//            print_r($_POST);
        } else {
            echo 'No data received';
        }
    }

    public function actionTimeTable($branchID, $serviceID, $_date = '') {
        if (Yii::app()->user->isGuest) {
            
        } else {
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
                        if (!(($time <= $breakstart && $time_end <= $breakstart) ||
                                ($time >= $breakend && $time_end >= $breakend))) {
                            //Checks that the timings are not during his break;
                            $availability = false;
                        } elseif (!($time >= $time_start && $time_end <= $end)) {
                            //Checks that the timings are within his working hours

                            $availability = false;
                        } else {
                            foreach ($employee->appointments as $appointment) {
                                if (!$appointment->appointment_cancelation) {
                                    if (!(($time <= strtotime($appointment->appointment_start_time) && $time_end <= strtotime($appointment->appointment_start_time)) ||
                                            ($time >= strtotime($appointment->appointment_end_time) && $time_end >= strtotime($appointment->appointment_end_time)))) {
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

    public function actionClientDetails($phone) {
        $user = User::model()->find("user_mobile_num = $phone");
        $output = '';
        if ($user) {
            $output .= "<h1>Registered User</h1>";
            $appointments = $user->appointments;
            $booked = count($appointments);
            $cancelled = 0;
            $attended = $booked;
            $skipped = 0;
            $cancelRatio = '0.00%';
            $skipRatio = '0.00%';
            $attendRatio = '0.00%';
            foreach ($appointments as $appointment) {
                $cancellation = $appointment->appointment_cancelation;
                if ($cancellation) {
                    if ($cancellation == 'absent') {
                        $skipped++;
                    } else {
                        $cancelled++;
                    }
                }
            }
            $attended -= ($cancelled + $skipped);
            if ($booked != 0) {
                $cancelRatio = number_format($cancelled / $booked * 100, 2) . '%';
                $skipRatio = number_format($skipped / $booked * 100, 2) . '%';
                $attendRatio = number_format(100 - ($skipped / $booked * 100), 2) . '%';
            }
            $output .= "Name: $user->user_name</br>Phonenumber: $user->user_mobile_num</br>
                    Gender: $user->user_gender</br>Amount of total booked: $booked</br>
                    Amount of total attended: $attended</br>Amount of total cancelled: $cancelled
                    </br>Amount of total skipped: $skipped</br>Attendance Percentage: $attendRatio
                    </br>Cancellation Percentage: $cancelRatio</br>Skip Percentage: $skipRatio";
        } else {
            $output .= "<h1>Non-Registered User</h1>";
            $appointments = Appointment::model()->findAll("user_phone = '$phone'");
            $booked = count($appointments);
            $cancelled = 0;
            $attended = $booked;
            $skipped = 0;
            $cancelRatio = '0.00%';
            $skipRatio = '0.00%';
            $attendRatio = '0.00%';
            $names = array();
            foreach ($appointments as $appointment) {
                $cancellation = $appointment->appointment_cancelation;
                if ($cancellation) {
                    if ($cancellation == 'absent') {
                        $skipped++;
                    } else {
                        $cancelled++;
                    }
                }
                $name = $appointment->user_name;
                if (!in_array($name, $names)) {
                    $names[] = $name;
                }
            }
            $attended -= ($cancelled + $skipped);
            if ($booked != 0) {
                $cancelRatio = number_format($cancelled / $booked * 100, 2) . '%';
                $skipRatio = number_format($skipped / $booked * 100, 2) . '%';
                $attendRatio = number_format(100 - ($skipped / $booked * 100), 2) . '%';
            }
            $output .= "Phonenumber: $phone</br>Amount of total booked: $booked</br>
                    Amount of total attended: $attended</br>Amount of total cancelled: $cancelled
                    </br>Amount of total skipped: $skipped</br>Attendance Percentage: $attendRatio
                    </br>Cancellation Percentage: $cancelRatio</br>Skip Percentage: $skipRatio</br>";
            $output .= "Names registered under this number:<ul>";
            foreach ($names as $name) {
                $output .= "<li>$name</li>";
            }
            $output .= "</ul>";
        }
        $this->render('clientDetails', array(
            'output' => $output,
        ));
    }

    public function actionSearchUser() {
        if (isset($_POST['mobile'])) {
            $mobile = $_POST['mobile'];
            $user = User::model()->find("user_mobile_num = '$mobile'");
            if ($user) {
                echo $user->user_name;
            } else {
                echo "404";
            }
        }
    }

}