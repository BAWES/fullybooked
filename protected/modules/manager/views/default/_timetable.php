<?php
echo $output;
?>
<script>
    $(document).ready(function() {
        $('#user_mobile').keyup(function() {
            var uMobile = $(this).val();
            var txtLength = uMobile.length;
            $('#user_name').attr("disabled", "disabled");
            if (txtLength === 8) {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('manager/default/searchUser') ?>', //this is the request page of ajax
                    data: {mobile: uMobile}, //data for throwing the expected url
                    type: 'POST', //you can also use GET method
                    dataType: 'html', // you can also specify for the result for json or xml
                    success: function(response) {
                        if (response === '404') {
                            $('#user_name').removeAttr("disabled");
                            $('#user_name').val('');
                        } else {
                            $('#user_name').val(response);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert('Ajax error occurred');
                    }});
            } else {
                $('#user_name').attr("disabled", "disabled");
                $('#user_name').val('');
            }
        });

        $('#booking #employees a').click(function() {
            if (!$(this).hasClass('unavailable')) {
                var timeBlock = $(this);
                $('#dialog-confirm #d_time').html(timeBlock.html());
                $('#dialog-confirm #d_employee').html(timeBlock.siblings('h3').html());
                var emp_id = timeBlock.siblings('h3').attr('name');
                var time = timeBlock.html();
                var currentDate = $('#datepicker').datepicker('getDate');
                if (!currentDate) {
                    currentDate = new Date().toDateString();
                }
                $("#dialog-confirm").dialog({
                    resizable: false,
                    //height:140,
                    width: 325,
                    modal: true,
                    buttons: {
                        "Confirm": function() {
                            var mobile = $('#user_mobile').val();
                            var name = $('#user_name').val();
                            $.ajax({
                                url: '<?php echo Yii::app()->createUrl('manager/default/book') ?>', //this is the request page of ajax
                                data: {employee_id: emp_id, service_id: selectedService, date: currentDate, starttime: time, user_phone: mobile, user_name: name}, //data for throwing the expected url
                                type: 'POST', //you can also use GET method
                                dataType: 'html', // you can also specify for the result for json or xml
                                success: function(response) {
                                    $('#dialog-message').attr('title', 'Error');
                                    if (response.indexOf("Error") == -1) {
                                        //timeBlock.addClass('unavailable'); 
                                        $('#dialog-message').attr('title', 'Appointment Confirmed');
                                    }
                                    $("#dialog-message").html(response);

                                    $("#dialog-message").dialog({
                                        modal: true,
                                        buttons: {
                                            Ok: function() {
                                                $(this).dialog("close");
                                            }
                                        }
                                    });

                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    $('#dialog-confirm').dialog("close");
                                    $("#dialog-message").html(xhr.responseText);
                                    $('#dialog-message').attr('title', 'Error');
                                    $("#dialog-message").dialog({
                                        modal: true,
                                        buttons: {
                                            Ok: function() {
                                                $(this).dialog("close");
                                            }
                                        }
                                    });
                                }});
                            $(this).dialog("destroy");
                            didSelect();
                        },
                        Cancel: function() {
                            $(this).dialog("destroy");
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
    <p style='font-weight:bold;'>User Mobile: <input type="text" style="display: inline-block;" id="user_mobile" maxlength="8"></input></p>
    <p style='font-weight:bold;'>User Name: <input type="text" style="display: inline-block;" id="user_name" disabled></input></p>
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