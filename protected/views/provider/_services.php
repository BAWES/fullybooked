<h2>Services</h2>
<?php
/* @var $branch Branch */
$services = array();
foreach ($branch->employees as $employee) {
    foreach ($employee->services as $service) {
        if (!isset($services[$service->service_id])) {
            $services[$service->service_id] = $service;
        }
    }
}
if (count($services) == 0) {
    echo 'No available services';
} else {
    foreach ($services as $service) {
        echo "<div>
                <h3>$service->service_name</h3>
                <h4><span>Duration $service->service_duration Minutes</span>&nbsp;&nbsp;-&nbsp;&nbsp;$service->service_price KD</h4>
                <p>$service->service_description</p>
                <a href='#' onclick=\"
                    selectedService = $service->service_id;
                    
                    var currentDate = new Date().toDateString();
                    $('#datepicker').datepicker('setDate', new Date());
                    $.ajax({
                    url:'" . Yii::app()->createUrl('provider/timetable') . "',//this is the request page of ajax
                    data:{branchID:$branch->branch_id, serviceID: selectedService, _date: currentDate},//data for throwing the expected url
                    type:'GET',//you can also use GET method
                    dataType:'html',// you can also specify for the result for json or xml
                    success:function(response){
                         if(response){
                             $('#booking').show();
                             $('#employees').html(response);
                         } else {
                             $('html, body').animate({ scrollTop: 0 }, 0);
                             $('#account').trigger('click');
                             $('#loginUsername').trigger('focus');
                         }
                    },
                    error:function(){
                         alert('Failed request data from ajax page');
                }}); window.location.hash = '#booking'; return false;\">Check Availibility</a>
            </div>";
    }
}
?>