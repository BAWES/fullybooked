<?php
echo $output;
?>
<script>
    $(document).ready(function(){
        $('#booking #employees a').click(function(){
            if(!$(this).hasClass('unavailable')){
                var timeBlock = $(this);
                $( '#dialog-confirm #d_time').html(timeBlock.html());
                $( '#dialog-confirm #d_employee').html(timeBlock.siblings('h3').html());
                var emp_id = timeBlock.siblings('h3').attr('name');
                var time = timeBlock.html();
                
                var currentDate = $( '#datepicker' ).datepicker( { dateFormat: 'yy-mm-dd' } ).val();
                
                if(!currentDate){
                    currentDate = new Date().toDateString();
                }
                $( "#dialog-confirm" ).dialog({
                    resizable: false,
                    //height:140,
                    modal: true,
                    buttons: {
                        "Confirm": function() {
                            $.ajax({
                                cache:false,
                                url:'<?php echo Yii::app()->createUrl('appointment/book') ?>',//this is the request page of ajax
                                data:{employee_id: emp_id, service_id:selectedService, date: currentDate, starttime:time},//data for throwing the expected url
                                type:'POST',//you can also use GET method
                                dataType:'html',// you can also specify for the result for json or xml
                                success:function(response){
                                    $( '#dialog-message' ).attr('title', 'Error');
                                    if(response.indexOf("Error") == -1){
                                        //timeBlock.addClass('unavailable'); 
                                        $( '#dialog-message' ).attr('title', 'Appointment Confirmed');
                                    }
                                    response = response.replace('Error:', '');
                                    $( "#dialog-message" ).html(response);
                                    
                                    $( "#dialog-message" ).dialog({
                                        modal: true,
                                        buttons: {
                                            Ok: function() {
                                                $( this ).dialog( "close" );
                                            }
                                        }
                                    });
                                    
                                },
                                error:function(xhr, ajaxOptions, thrownError){
                                    $( '#dialog-confirm' ).dialog( "close" );
                                    $( "#dialog-message" ).html(thrownError);
                                    $( '#dialog-message' ).attr('title', 'Error');
                                    $( "#dialog-message" ).dialog({
                                        modal: true,
                                        buttons: {
                                            Ok: function() {
                                                $( this ).dialog( "close" );
                                            }
                                        }
                                    });
                                }});
                                $( this).dialog("close");
                                didSelect();
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
            }
            return false;
        });
    });
    
</script>
<div id="dialog-confirm" title="Appointment Request" style='display:none;'>
    <p style='font-weight:bold;'>Company name: <span style='font-weight:normal;'><?php echo $branchModel->provider->provider_name ?></span></p>
    <p style='font-weight:bold;'>Location: <span style='font-weight:normal;'><?php echo $branchModel->location->location_name ?></span></p>
    <p style='font-weight:bold;'>Date: <span style='font-weight:normal;'><?php echo $date ?></span></p>
    <p style='font-weight:bold;'>Time: <span style='font-weight:normal;' id="d_time"></span></p>
    <p style='font-weight:bold;'>Employee: <span style='font-weight:normal;' id="d_employee"></span></p>
</div>
<div id="dialog-message" title="Appointment Confirmed" style='display:none;'>
    <p>
        <span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
        Your appointment is confirmed.
    </p>
    <p>
        <b>Appointment ID - 25631</b>
    </p>
</div>