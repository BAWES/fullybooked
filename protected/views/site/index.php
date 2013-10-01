<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
/*
 * 	<script src='js/jquery-1.8.3.min.js' type='text/javascript'></script>
  <script src="js/jquery.nivo.slider.js" type="text/javascript"></script>
  <script src='js/khalidm.js' type='text/javascript'></script>
 */
$cs->registerScriptFile($baseUrl . '/js/jquery.nivo.slider.js');
$cs->registerCssFile($baseUrl . '/css/nivo-slider.css');
$cs->registerCssFile($baseUrl . '/css/themes/default/default.css');
$cs->registerScript('script', "
	//setup nivo slider
    $('#slider').nivoSlider({
    	effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
        animSpeed: 500, // Slide transition speed
        startSlide: 0, // Set starting Slide (0 index)
        directionNav: true, // Next & Prev navigation
        controlNav: false, // 1,2,3... navigation
        controlNavThumbs: false, // Use thumbnails for Control Nav
        pauseOnHover: true, // Stop animation while hovering
        manualAdvance: false, // Force manual transitions
        prevText: 'Prev', // Prev directionNav text
        nextText: 'Next', // Next directionNav text
        pauseTime: 3000 // How long each slide will show
    });
    $('#dateInput2').datepicker({dateFormat: 'dd-mm-yy', minDate: 0,});
    $('#ui-datepicker-div').addClass('fixDatePosition');
    "
        , CClientScript::POS_END);
?>
<section id='content'>

    <!-- Slider -->
    <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/slider/banner.jpg" alt="" />
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/slider/banner2.jpg" alt="" />
        </div>
    </div>

    <!-- Two Column -->
    <div class='twoColumn'>
        <!-- Left Column -->
        <div class='div1'>
            <h1 class='check-icon'>Book Appointments</h1>

            <form action="<?php echo Yii::app()->createUrl('appointment/search'); ?>" >
                <table>
                    <tr>
                        <td>Industry</td>
                        <td>
                            <select name="industry">
                                <option value="">All</option>
                                <?php
                                foreach ($industries as $_industry) {
                                    echo "<option value=$_industry->category_id>$_industry->category_name</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>Area</td>
                        <td>
                            <select name="location">
                                <option value="">All</option>
                                <?php
                                foreach ($locations as $_location) {
                                    echo "<option value=$_location->location_id>$_location->location_name</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>
                            <input type="text" name="date" id="dateInput2" value="<?php
                            if (isset($_GET['date'])) {
                                echo $_GET['date'];
                            }
                            ?>"/>
                        </td>
                        <td>Time</td>
                        <td>
                            <select name="time">
                                <option value="">All</option>
                                <?php
                                foreach ($times as $_time) {
                                    echo "<option>$_time</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <input type='submit' value='Check Availability' id='checkAvailable' class='btn-large'/>
            </form>
        </div>
        <!-- End Left Column -->

        <!-- Right Column -->
        <?php if (Yii::app()->user->isGuest) { ?>
            <div class='div2'>
                <h2 class='keyIcon'>Login <a href='<?php echo Yii::app()->createUrl("user/register"); ?>'>new user <span>signup</span></a></h2>
                <section class='textbox'>
                    <form method='post' action="<?php echo $this->createUrl("site/login") ?>">
                        <table>
                            <tr>
                                <td>Email</td><td><input type='text' name='LoginForm[username]'/></td>
                            </tr>
                            <tr>
                                <td class='second'>Password</td><td class='second'><input type='password' name='LoginForm[password]'/></td>
                            </tr>
                        </table>

                        <div class='loginBtns'>
                            <input type='submit' value='Login' class='btn-small'/>
                            <a href='#'>Forgot Password</a>
                        </div>
                    </form>
                </section>
            </div>
            <?php
        } else {
            echo "<div class='div2'>
            <h2>Upcoming Appointments</h2>
            <section class='textbox logged-in'>";
            if (count($days) == 0) {
                echo "No upcoming appointments";
            } else {
                //            <ul>
//                    <li><p>Today</p>
//                        <ol>
//                            <li>2.00 Pm - Casa Spa</li>
//                            <li>6.00 Pm - Bayan Dental</li>
//                        </ol>
//                    </li>
//                    <li><p>25/12/2012</p>
//                        <ol>
//                            <li>7.00 Pm - Hair Salon</li>
//                        </ol>
//                    </li>
//                    <li><p>30/12/2012</p>
//                        <ol>
//                            <li>10.00 Am - Pedicure</li>
//                        </ol>
//                    </li>
//                </ul>
                echo "<ul>";
                foreach ($days as $day => $apps) {
                    if ($day == date('d/m/Y')) {
                        echo "<li><p>Today</p>";
                    } else {
                        echo "<li><p>$day</p>";
                    }
                    echo '<ol>';
                    foreach ($apps as $app) {
                        echo "<li>" . date('g.i a', strtotime($app->appointment_start_time)) . " - " . $app->service->service_name . " at " . $app->employee->branch->provider->provider_name . "</li>";
                    }
                    echo '</ol></li>';

//                    foreach ($months as $month => $amount) {
//                        echo '<h2>' . $month . ': ' . $amount . 'KD</h2>';
//                    }
                }
            }
            echo "</section></div>";
        }
        ?>
        <!-- End Right Column -->
    </div>
    <!-- End Two Columns -->
    <br class='clear'/>


</section>
