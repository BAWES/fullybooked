<?php 
if(!isset($_GET['id'])){
    echo "<h1>Add Appointment</h1>";
} else {
    echo "<h1>Add Appointment for " . $model->location->location_name ."</h1>";
}
?>
<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCssFile($baseUrl . '/css/custom-theme/jquery-ui-1.10.1.custom.css', 'screen');
$cs->registerCssFile($baseUrl . '/css/fmain.css', 'screen');
$cs->registerCoreScript('jquery.ui');
$this->renderPartial('_appointmentForm', array(
    'model' => $model,
    'branches' => $branches,
    'services' => $services,
));
$cs->registerScript('start', "
        var days= ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        var selectedService;
        function didSelect(){
            var currentDate = $( '#datepicker' ).datepicker( 'getDate' );
            var today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);
            today.setMilliseconds(0);
            if(currentDate < today){
                $( '#dialog-message' ).html('Cannot select a date in the past');
                $( '#dialog-message' ).attr('title', 'Error');
                                    $( '#dialog-message' ).dialog({
                                        modal: true,
                                        buttons: {
                                            Ok: function() {
                                                $( this ).dialog( 'close' );
                                                $( '#datepicker' ).datepicker( 'setDate', today );
                                                didSelect();
                                            }
                                        }
                                    });
            } else {
                    $.ajax({
                        url:'".Yii::app()->createUrl('manager/default/timetable')."',//this is the request page of ajax
                        data:{branchID:$model->branch_id, serviceID: selectedService, _date:currentDate},//data for throwing the expected url
                        type:'GET',//you can also use GET method
                        dataType:'html',// you can also specify for the result for json or xml
                        success:function(response){
                             $('#employees').html(response);
                        },
                        error:function(){
                             alert('Failed request data from ajax page');
                    }});
            }
        }
        $('#datepicker').datepicker({
            onSelect: function(){
                didSelect();
                
                },
            minDate: 0    // this will be replaced with the ajax function call
        });
        $('#service').change(function(){
            selectedService = $('#service option:selected').val();
            if(selectedService){
                var currentDate = new Date().toDateString();
                    $('#datepicker').datepicker('setDate', new Date());
                    $.ajax({
                    url:'" . Yii::app()->createUrl('manager/default/timetable') . "',//this is the request page of ajax
                    data:{branchID:$model->branch_id, serviceID: selectedService, _date: currentDate},//data for throwing the expected url
                    type:'GET',//you can also use GET method
                    dataType:'html',// you can also specify for the result for json or xml
                    success:function(response){
                         if(response){
                             $('#booking').show();
                             $('#employees').html(response);
                         }
                    },
                    error:function(){
                         alert('Failed request data from ajax page');
                }});
            } else {
                location.reload();
            }
        });
        $('#branch').change(function(){
            selectedBranch = $('#branch option:selected').val();
            if(selectedBranch){
                window.location.replace('". Yii::app()->createUrl("manager/default/addAppointment") ."/id/' + selectedBranch);
            } else {
                location.reload();
            }
        });
	"
        , CClientScript::POS_END);
?>
<div id='booking' hidden>
    <h2>Select Date, Person & Time</h2>

    <div id='legends'>
        <p class='available'>Available</p>
        <p class='unavailable'>Unavailable</p>
    </div>

    <div id="datepicker"></div>

    <div id='employees'>

    </div>
</div>
