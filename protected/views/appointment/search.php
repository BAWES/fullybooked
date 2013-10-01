<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
//$cs->registerScriptFile($baseUrl . '/js/jquery.columnizer.js');
//$cs->registerCoreScript('jquery');
$cs->registerScript('dateinput', "
            $('#dateInput').datepicker({dateFormat: 'dd-mm-yy', minDate: 0});
            $('#ui-datepicker-div').addClass('fixDatePosition2');
", CClientscript::POS_END);
?>
<section id='content' class='pad'>

    <div id='leftColumn'>

        <h1 class='check-icon'>Book Appointments</h1>
        <form>
            <table id='search'>
                <tr>
                    <td>Industry</td>
                    <td>
                        <select name="industry">
                            <option value="">All</option>
                            <?php
                            foreach ($industries as $_industry) {
                                if (isset($_GET['industry']) && $_GET['industry'] == $_industry->category_id) {
                                    echo "<option selected='selected' value=$_industry->category_id>$_industry->category_name</option>";
                                } else {
                                    echo "<option value=$_industry->category_id>$_industry->category_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Area</td>
                    <td>
                        <select name="location">
                            <option value="">All</option>
                            <?php
                            foreach ($locations as $_location) {
                                if (isset($_GET['location']) && $_GET['location'] == $_location->location_id) {
                                    echo "<option selected='selected' value=$_location->location_id>$_location->location_name</option>";
                                } else {
                                    echo "<option value=$_location->location_id>$_location->location_name</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>
                        <input type="text" name="date" id="dateInput" value="<?php
                            if (isset($_GET['date'])) {
                                echo $_GET['date'];
                            }
                            ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>
                        <select name="time">
                            <option value="">All</option>
<?php
foreach ($times as $_time) {
    if (isset($_GET['time']) && $_GET['time'] == $_time) {
        echo "<option selected='selected' value='$_time'>$_time</option>";
    } else {
        echo "<option value='$_time'>$_time</option>";
    }
}
?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type='submit' class='btn-large' value='Check Results'/>
        </form>
<?php if ($results) { ?>
            <section id='searchResults'>
                <h1>Search Results</h1>
    <?php echo $results; ?>
                <!--
                <div>
                    <img src='<?php echo Yii::app()->request->baseUrl; ?>/images/providers/redrock.jpg'/>
                    <p>Red Rock Nail Spa</p>
                    <a href='#' class='btn-tiny'>Book Now</a>
                </div>
                <div>
                    <img src='<?php echo Yii::app()->request->baseUrl; ?>/images/providers/spaloon.jpg'/>
                    <p>Spaloon</p>
                    <a href='#' class='btn-tiny'>Book Now</a>
                </div>
                <div>
                    <img src='<?php echo Yii::app()->request->baseUrl; ?>/images/providers/asnan.jpg'/>
                    <p>Asnan Clinic</p>
                    <a href='#' class='btn-tiny'>Book Now</a>
                </div>
                <div>
                    <img src='<?php echo Yii::app()->request->baseUrl; ?>/images/providers/prive.jpg'/>
                    <p>Prive Nail Spa</p>
                    <a href='#' class='btn-tiny'>Book Now</a>
                </div>
                <div>
                    <img src='<?php echo Yii::app()->request->baseUrl; ?>/images/providers/jenny.jpg'/>
                    <p>Jenny Spa</p>
                    <a href='#' class='btn-tiny'>Book Now</a>
                </div>
                -->
            </section>
<?php } ?>
    </div>
    <div id='rightColumn'>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad1.jpg'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad2.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad4.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad3.gif'/></a>
    </div>

    <br class='clear'/>
</section>