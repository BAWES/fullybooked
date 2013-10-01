<?php 
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
/*
 <link href='../fullcalendar/fullcalendar.css' rel='stylesheet' />
 <link href='../fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
 */
$cs->registerCoreScript('jquery');
$cs->registerCssFile($baseUrl . '/css/custom-theme/jquery-ui-1.10.1.custom.css', 'screen');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/fullcalendar/fullcalendar.min.js');
$cs->registerCssFile($baseUrl . '/fullcalendar/fullcalendar.print.css', 'print');

$cs->registerCssFile($baseUrl . '/fullcalendar/fullcalendar.css');
$cs->registerScript('script', "
	$(document).ready(function() {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
                        header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
                        allDayDefault: false,
			events: [
				$events
			],
                        eventClick: function(calEvent, jsEvent, view) {
                            $( '#d_provider' ).html(calEvent.provider);
                            $( '#d_location' ).html(calEvent.location);
                            $( '#d_date' ).html(calEvent.adate);
                            $( '#d_time' ).html(calEvent.time);
                            $( '#d_employee' ).html(calEvent.employee);
                            $( '#d_service' ).html(calEvent.service);
                            var status = 'user';
                            var appid = calEvent.id;
                            $( '#dialog-details' ).dialog({
                                        modal: true,
                                        buttons: {
                                            'Cancel Appointment': function() {
                                                $( '#dialog-confirm' ).dialog({
                                                    modal: true,
                                                    buttons: {
                                                        Yes: function(){
                                                            $.ajax({
                                                    url:'" . Yii::app()->createUrl('appointment/cancelAppointment') . "',//this is the request page of ajax
                                                    data:{id: appid, status: status},//data for throwing the expected url
                                                    type:'POST',//you can also use GET method
                                                    dataType:'html',// you can also specify for the result for json or xml
                                                    success:function(response){
                                                        if(response.indexOf('Error') == -1){
                                                            $('#calendar').fullCalendar( 'removeEvents' , calEvent.id );
                                                            
                                                        }
                                                        response = response.replace('Error:', '');
                                                        $( '#dialog-message' ).html(response);

                                                        $( '#dialog-message' ).dialog({
                                                            modal: true,
                                                            buttons: {
                                                                Ok: function() {
                                                                    $( this ).dialog( 'close' );
                                                                }
                                                            }
                                                        });
                                                        $( '#dialog-details').dialog('close');
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
                                                $( this).dialog('close');
                                                        },
                                                        No: function(){
                                                            $( this ).dialog('close');
                                                        }
                                                    }
                                                });
                                            },
                                            Ok: function() {
                                                $( this ).dialog( 'close' );
                                            }
                                        }
                                    });
                        }
		});
		
	});"
        , CClientScript::POS_END);
?>

<!DOCTYPE html>
<html>
<head>
<style>
	#calendar {
		width: 900px;
		margin: 0 auto;
		}
</style>
</head>
<body>
<div id='calendar'></div>
<div id="dialog-details" title="Appointment Details" style='display:none;'>
    <p style='font-weight:bold;'>Company name: <span style='font-weight:normal;' id="d_provider"></span></p>
    <p style='font-weight:bold;'>Location: <span style='font-weight:normal;' id="d_location"></span></p>
    <p style='font-weight:bold;'>Date: <span style='font-weight:normal;' id="d_date"></span></p>
    <p style='font-weight:bold;'>Time: <span style='font-weight:normal;' id="d_time"></span></p>
    <p style='font-weight:bold;'>Employee: <span style='font-weight:normal;' id="d_employee"></span></p>
    <p style='font-weight:bold;'>Service: <span style='font-weight:normal;' id="d_service"></span></p>
</div>
<div id="dialog-message" title="Cancellation Status" style='display:none;'></div>
<div id="dialog-confirm" title="Cancellation Confirmation" style='display: none'>
    Are you sure you want to cancel this appointment?
</div>
</body>
</html>