<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCssFile($baseUrl . '/css/custom-theme/jquery-ui-1.10.1.custom.css', 'screen');
$cs->registerCoreScript('jquery.ui');
$cs->registerScript('cancel', "
        $('#appointmentsTable .cancel a').click(function(event){
            event.preventDefault();
            var appid = $(this).attr('href');
            var status = 'provider';
            if ($(this).html() != 'Cancel'){
                status = 'absent';
            }
            $( '#dialog-confirm' ).dialog({
            resizable: false,
                modal: true,
                buttons: {
                    'Confirm': function() {
                        $.ajax({
                            url:'" . Yii::app()->createUrl('manager/default/cancelAppointment') . "',//this is the request page of ajax
                            data:{id: appid, status: status},//data for throwing the expected url
                            type:'POST',//you can also use GET method
                            dataType:'html',// you can also specify for the result for json or xml
                            success:function(response){
                                $( '#dialog-message' ).attr('title', 'Error');
                                if(response.indexOf('Error') == -1){

                                }
                                $( '#dialog-message' ).html(response);

                                $( '#dialog-message' ).dialog({
                                    modal: true,
                                    buttons: {
                                        Ok: function() {
                                            $( this ).dialog( 'close' );
                                            location.reload();
                                        }
                                    }
                                });
                            },
                            error:function(xhr, ajaxOptions, thrownError){
                                $( '#dialog-confirm' ).dialog( 'close' );
                                $( '#dialog-message' ).html(thrownError);
                                $( '#dialog-message' ).attr('title', 'Error');
                                $( '#dialog-message' ).dialog({
                                    modal: true,
                                    buttons: {
                                        Ok: function() {
                                            $( this ).dialog( 'close' );
                                        }
                                    }
                                });
                            }
                        });
                    },
                    Cancel: function() {
                        $( this ).dialog( 'close' );
                    }
                }    
            });
        });
        ", CClientScript::POS_END);


$this->layout = 'column2';
$this->breadcrumbs = array(
    $this->module->id,
);
$this->menu = $menuArray;

if (isset($branch)) {
    echo "<h1>" . $branch->location->location_name . "<span style=\"font-size: 12px;\"><a href='". Yii::app()->createUrl("manager/default/addAppointment", array('id'=>$branch->branch_id)) ."'>(add appointment)</a></span></h1>";
    if (count($appointmentList) > 0) {
        echo "<div style='float:left;'><ul>";
        foreach ($appointmentList as $date => $appointments) {
            if ($date == date('Y-m-d')) {
                echo "<li><a href='" . Yii::app()->createUrl("manager/default/index", array('id' => $branch->branch_id, 'date' => $date)) . "'>Today (". count($appointments) .")</a></li>";
            } else {
                echo "<li><a href='" . Yii::app()->createUrl("manager/default/index", array('id' => $branch->branch_id, 'date' => $date)) . "'>$date (". count($appointments) .")</a></li>";
            }
        }
        echo '</ul></div>';
        if (isset($_GET['date'])) {
            if (isset($appointmentList[$_GET['date']])) {
                echo "<div style='margin-left:130px'><table id='appointmentsTable'>";
                foreach ($appointmentList[$_GET['date']] as $appointment) {
                    //echo "<tr><td>$appointment->appointment_start_time for " . $appointment->user_name . '</td>';
                    echo "<tr>";
                    echo "<td style='width:400px'>" . date('g:i a', strtotime($appointment->appointment_start_time)) . " - " . date('g:i a', strtotime($appointment->appointment_end_time));
                    echo " with " . $appointment->employee->employee_name ."</br>";
                    echo "Client: $appointment->user_name Phone: <a href='" . Yii::app()->createUrl("manager/default/clientDetails", array('phone'=>$appointment->user_phone)) ."' target='_blank'>$appointment->user_phone</a></br>Service: " . $appointment->service->service_name;
                    echo "<td class='cancel'><a href='" . $appointment->appointment_id . "'>Mark Absent</a></td>";
                    echo "<td class='cancel'><a href='" . $appointment->appointment_id . "'>Cancel</a></td>";
                    echo "</tr>";
                }
                echo '</table></div>';
            }
        }
    }
} else {
    echo "<h1>Appointments <span style=\"font-size: 12px;\"><a href='". Yii::app()->createUrl("manager/default/addAppointment") ."'>(add appointment)</a></span></h1>";
    echo "<p>Please choose a branch from the right menu.</p>";
}
?>
<div id="dialog-confirm" title="Appointment Cancellation" style='display:none;'>
    <p>Are you sure you want to cancel?</p>
</div>
<div id="dialog-message" title="Cancellation Status" style='display:none;'>
    <p>
        <span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
        Your appointment has been cancelled.
    </p>
    <p>
        <b>Cancelled Appointment ID - 25631</b>
    </p>
</div>