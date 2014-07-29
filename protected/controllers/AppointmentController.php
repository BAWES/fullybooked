<?php

class AppointmentController extends FController {

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
            array('deny', // deny all guest users which are not authenticated users
                'users' => array('?'),
            ),
        );
    }

    //AJAX Request to show services offered by employees at that branch/date
    public function actionServices($branch, $date) {
        //If no employees available on day, show error message
        $branch = (int) $branch;
        $date = strtotime($date);

        $weekDay = date('l', $date); //Saturday

        $criteria = new CDbCriteria();
        $criteria->condition = "branch_id =" . $branch;
        $criteria->with = "services";
        $criteria->order = "services.service_name";

        $employeesAvailable = Employee::model()->available($weekDay)->findAll($criteria);

        $servicesAvailable = array();

        foreach ($employeesAvailable as $employee) {
            foreach ($employee->services as $service) {
                $servicesAvailable[$service->service_id] = array(
                    'name' => $service->service_name,
                    'price' => $service->service_price,
                    'duration' => $service->service_duration,
                    'description' => $service->service_description,
                );
            }
        }

        if (count($servicesAvailable)) {
            $output = "<ul id='services'>";
            foreach ($servicesAvailable as $serviceId => $service) {
                $output .= "<li><a href='#" . $serviceId . "'>" . $service['name'] . " (KD " . $service['price'] . ") - " .
                        $service['duration'] . " minutes</a><br/>" . $service['description'] . "</li>";
            }
            $output .= "</ul>";
            echo $output;
        }else
            echo "No services available";
    }

    //Book appointment at the following provider
    public function actionProvider($id) {
        $provider = Provider::model()->findByPk($id);

        //load provider's branches and send to render as dropdown list.
        $this->render('provider', array(
            'provider' => $provider,
        ));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionSearch($industry = null, $location = null, $date = null, $time = null) {
        $industries = Category::model()->findAll();
        $locations = Location::model()->findAll();
        $results = array();

        $times = array();

        for ($i = 8; $i < 22; $i++) {
            $times[] = date("h:iA", strtotime("$i:00")) . " - " . date("h:iA", strtotime(($i + 1) . ":00"));
        }

        $results = null;
        if (isset($date) || isset($time) || isset($location) || isset($industry)) {
            if (!$date && !$time && !$location && !$industry) {
                $providers = Provider::model()->findAll();
                if ($providers) {
                    foreach ($providers as $provider) {
                        if (date('Y-m-d', strtotime($provider->provider_booking_enddate)) >= date('Y-m-d') &&
                                date('Y-m-d', strtotime($provider->provider_booking_startdate)) <= date('Y-m-d')) {
                            $results .= "<div>
                                <img src='" . Yii::app()->request->baseUrl . "/images/provider/thumb/" . $provider->provider_logo . "'/>
                                <p>" . $provider->provider_name . "</p>
                                <a href='" . Yii::app()->createUrl("provider/view", array('id' => $provider->provider_id)) . "' class='btn-tiny'>Book Now</a>
                            </div>";
                        }
                    }
                }
            }
            if (!$date && !$time) {
                if ($industry && $location) {
                    $industry = (int) $industry;
                    $location = (int) $location;
                    $criteria = new CDbCriteria();
                    $criteria->condition = "location_id = $location";
                    $criteria->with = array(
                        'provider' => array(
                            // we don’t want to select posts
                            // but want to get only users with published posts
                            'joinType' => 'INNER JOIN',
                            'condition' => "provider.category_id=$industry",
                            ));
//
                    $branches = Branch::model()->findAll($criteria);
                    if ($branches) {
                        foreach ($branches as $branch) {
                            if (date('Y-m-d', strtotime($branch->provider->provider_booking_enddate)) >= date('Y-m-d') &&
                                    date('Y-m-d', strtotime($branch->provider->provider_booking_startdate)) <= date('Y-m-d')) {
                                $results .= "<div>
                                <img src='" . Yii::app()->request->baseUrl . "/images/provider/thumb/" . $branch->provider->provider_logo . "'/>
                                <p>" . $branch->provider->provider_name . "</p>
                                <a href='" . Yii::app()->createUrl("provider/view", array('id' => $branch->provider->provider_id)) . "' class='btn-tiny'>Book Now</a>
                            </div>";
                            }
                        }
                    }
                } elseif ($industry) {

                    $industry = (int) $industry;
                    $providers = Provider::model()->findAll("category_id=$industry");
                    if ($providers) {
                        foreach ($providers as $provider) {
                            if (date('Y-m-d', strtotime($provider->provider_booking_enddate)) >= date('Y-m-d') &&
                                    date('Y-m-d', strtotime($provider->provider_booking_startdate)) <= date('Y-m-d')) {
                                $results .= "<div>
                                <img src='" . Yii::app()->request->baseUrl . "/images/provider/thumb/" . $provider->provider_logo . "'/>
                                <p>" . $provider->provider_name . "</p>
                                <a href='" . Yii::app()->createUrl("provider/view", array('id' => $provider->provider_id)) . "' class='btn-tiny'>Book Now</a>
                            </div>";
                            }
                        }
                    }
                } elseif ($location) {
                    $branches = Branch::model()->findAll("location_id=$location");
                    if ($branches) {
                        foreach ($branches as $branch) {
                            if (date('Y-m-d', strtotime($branch->provider->provider_booking_enddate)) >= date('Y-m-d') &&
                                    date('Y-m-d', strtotime($branch->provider->provider_booking_startdate)) <= date('Y-m-d')) {
                                $results .= "<div>
                                <img src='" . Yii::app()->request->baseUrl . "/images/provider/thumb/" . $branch->provider->provider_logo . "'/>
                                <p>" . $branch->provider->provider_name . "</p>
                                <a href='" . Yii::app()->createUrl("provider/view", array('id' => $branch->provider->provider_id)) . "' class='btn-tiny'>Book Now</a>
                            </div>";
                            }
                        }
                    }
                }
            } else {
                if ($date && $time) {
                    $_time = substr($time, 0, strpos($time, '-'));
                    $_date = date('Y-m-d H:i', strtotime($date . ' ' . $_time));
//                    $results .= $_date;
                } elseif ($date) {
                    $_date = date('Y-m-d', strtotime($date));
                } else {
                    $_time = substr($time, 0, strpos($time, '-'));
                    $_date = date('Y-m-d H:i', strtotime($_time));
//                    $results .= $_date;
                }
//                $nextDate = date('Y-m-d', strtotime($date) + 60 * 60 * 24);
                $condition = '';
                if ($industry) {
                    $condition = "category_id=$industry AND";
                }
                $availableProviders = Provider::model()->findAll("$condition provider_booking_enddate >= '$_date' && provider_booking_startdate <= '$_date'");


                foreach ($availableProviders as $provider) {
                    //make a table of times per provider, all unavailable and tick them off as you go through each service
                    //of a provider.
                    $timetable = array();
                    if ($date && $time) {
                        $_time = strtotime($_date);
                    } elseif ($date) {
                        $_time = strtotime($_date) + 60 * 60 * 8;
                        $time_end = strtotime($_date) + 60 * 60 * 22;
                    } else {
                        $_time = strtotime($_date);
                        $time_end = strtotime($_date) + 60 * 60 * 24 * 6;
                    }

//                    $results .= date('Y-m-d H:i', $time) . '</br>';
//                    $results .= date('Y-m-d H:i', $time_end) . '</br>';
                    if ($date && $time) {
                        $timetable["$_time"] = false;
                    } else {
                        while ($_time <= $time_end) {
                            $timetable["$_time"] = false;
                            if ($date) {
                                $_time += 60 * 60 * 0.5;
                            } else {
                                $_time += 60 * 60 * 24;
                            }
                        }
                    }
//                    $results .= '<pre>' . print_r($timetable, true) . '</pre>';
//                    $_date = date('Y-m-d', $time_end - 60 * 60 * 24 * 7);
//                    foreach($timetable as $hour => $value){
//                        $results .= date('Y-m-d H:i', $hour) . " -> $value</br>";
//                    }
                    $display = false;
                    foreach ($provider->branches as $branch) {
                        if ($display) {
                            break;
                        }
                        if ($location) {
                            if ($branch->location->location_id != $location) {
                                continue;
                            }
                        }
                        foreach ($branch->employees as $employee) {
                            if ($employee->employee_dayoff == date('l', $_time)) {
                                continue;
                            }
                            if ($display) {
                                break;
                            }
                            foreach ($employee->services as $service) {
                                if ($display) {
                                    break;
                                }
                                //Brace yourself

                                $_time = strtotime($employee->employee_workstart);
                                $_time = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $_time));


                                $end = strtotime($employee->employee_workend);
                                $end = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $end));
                                $breakstart = strtotime($employee->employee_breakstart);
                                $breakstart = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $breakstart));
                                $breakend = strtotime($employee->employee_breakend);
                                $breakend = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $breakend));
                                $time_start = strtotime($employee->employee_workstart);
                                $time_start = strtotime(date('Y-m-d', strtotime($_date)) . date('H.i', $time_start));

                                foreach ($timetable as $_time => $value) {
                                    if (!$value) {
                                        if (!$date) {
                                            $_date = date('Y-m-d', $_time);
                                            $time_start = strtotime($_date . date('H.i', $time_start));
                                            $end = strtotime($_date . date('H.i', $end));
                                            $breakstart = strtotime($_date . date('H.i', $breakstart));
                                            $breakend = strtotime($_date . date('H.i', $breakend));
                                        }
                                        if ($employee->employee_dayoff == date('l', $_time)) {
                                            continue;
                                        }
                                        $time_end = $_time + 60 * $service->service_duration;
                                        $availability = true;
                                        if (!(($_time <= $breakstart
                                                && $time_end <= $breakstart)
                                                ||
                                                ($_time >= $breakend
                                                && $time_end >= $breakend))) {
                                            //Checks that the timings are not during his break;
//                                            $results .= 'falsified1</br>';
                                            $availability = false;
                                        } elseif (!($_time >= $time_start
                                                && $time_end <= $end)) {
                                            //Checks that the timings are within his working hours
//                                            $results .= 'falsified2</br>';
//                                            $results .= date('Y-m-d H:i', $time_end) . ' --- ' . date('Y-m-d H:i', $end) . '</br>';
                                            $availability = false;
                                        } else {
                                            foreach ($employee->appointments as $appointment) {
                                                if (!$appointment->appointment_cancelation) {
                                                    if (!(($_time <= strtotime($appointment->appointment_start_time)
                                                            && $time_end <= strtotime($appointment->appointment_start_time))
                                                            ||
                                                            ($_time >= strtotime($appointment->appointment_end_time)
                                                            && $time_end >= strtotime($appointment->appointment_end_time)))) {
                                                        //Checks that the timings are not during another appointment
//                                                    $results .= 'falsified3</br>';
                                                        $availability = false;
                                                    }
                                                }
                                            }
                                        }
                                        if ($availability) {
                                            $timetable["$_time"] = $availability;
                                            $display = true;
                                            break;
                                        }
                                    } else {
                                        $display = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
//                    foreach ($timetable as $hour => $value) {
//                        $results .= date('Y-m-d H:i', $hour) . " -> $value</br>";
//                    }
                    if ($display) {
                        $results .= "<div>
                                <img src='" . Yii::app()->request->baseUrl . "/images/provider/thumb/" . $provider->provider_logo . "'/>
                                <p>" . $provider->provider_name . "</p>
                                <a href='" . Yii::app()->createUrl("provider/view", array('id' => $provider->provider_id)) . "' class='btn-tiny'>Book Now</a>
                            </div>";
                    }
//                    $results .= $provider->provider_name . "'s Timetable:</br>";
//                    $results .= '<pre>' . print_r($timetable, true) . '</pre>';
                }
            }
            if (!$results) {
                $results = "No available bookings found";
            }
        }

        $this->render('search', array(
            'industries' => $industries,
            'locations' => $locations,
            'times' => $times,
            'results' => $results,
        ));
    }

    public function actionBook() {
        /*
         * @property string $appointment_id
         * @property string $user_name
         * @property string $employee_id
         * @property string $service_id
         * @property string $user_id
         * @property string $user_phone
         * @property string $appointment_start_time
         * @property string $appointment_end_time
         * @property string $appointment_cancelation
         * @property string $appointment_source
         * employee_id:0, service_id:0, starttime:0
         */
        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($user->user_verif_code != '0') {
            echo '<p>Error: Please activate your account to be able to book appointments.</p>';
            echo "<p><a href='" . Yii::app()->createUrl("user/view") . "'>Click here to activate</a></p>";
        } elseif (isset($_POST)) {
            $employee = Employee::model()->findByPk($_POST['employee_id']);
            $service = Service::model()->findByPk($_POST['service_id']);
            
            error_log($_POST['date']);
            
            //Date we want to book at (fixing format for parsing first)
            
            //Current Date Format:
            //Tue Jul 29 2014 00:00:00 GMT+0300 (Arab Standard Time)
            //strtotime isn't working on it for some reason
            
            $dateInput = strtotime($_POST['date']);
            $date = date('Y-m-d', $dateInput);
            
            
            //Time we want to book at
            $starttime = strtotime($date . ' ' . $_POST['starttime']);

            //Provider allowed start and end date
            $providerBookingStartDate = date('Y-m-d', strtotime($employee->provider->provider_booking_startdate));
            $providerBookingEndDate = date('Y-m-d', strtotime($employee->provider->provider_booking_enddate));
            
            if ($providerBookingStartDate > date('Y-m-d', $starttime)) {
                echo $dateInput." - ".$_POST['date']."<br>";
                echo date('Y-m-d h:i a', $starttime);
                echo 'Error: Booking for this provider hasn\'t started yet';
            } elseif ($providerBookingEndDate < date('Y-m-d', $starttime)) {
                echo 'Error: Booking for this provider has already ended';
            } else {
//            echo "Employee: $employee->employee_id, User: $user->user_id, Service: $service->service_id, Start: $starttime";
                $appointment = new Appointment();
                $appointment->user_name = $user->user_name;
                $appointment->employee_id = $employee->employee_id;
                $appointment->service_id = $service->service_id;
                $appointment->user_id = $user->user_id;
                $appointment->user_phone = $user->user_mobile_num;
                $appointment->appointment_start_time = date('Y-m-d H:i', $starttime);
                $appointment->appointment_end_time = date('Y-m-d H:i', $starttime + 60 * $service->service_duration);
                $appointment->appointment_source = 'user';

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

    public function actionCancelAppointment() {
        if (isset($_POST['id']) && isset($_POST['status'])) {
            $id = (int) $_POST['id'];
            $status = $_POST['status'];
            if ($status == 'absent' || $status == 'user' || $status == 'provider') {
                $appointment = Appointment::model()->findByPk($id);
                $appointment->cancelAppointment($status);
            } else {
                echo "Error: Invalid status";
            }
        } else {
            echo 'Error: No data found';
        }
    }
}