<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/jquery.columnizer.js');
$cs->registerScript('filter', "
        var selectedBranch = 0;
        var selectedService = 0;
        function filter(){
            var category = $('#categoryMenu option:selected').val();
            var location = $('#locationMenu option:selected').val();
            var url = '" . $this->createUrl("directory/") . "';
            if(category != ''){
                url += '?category=' + category;
                if(location != ''){
                    url += '&location=' + location;
                }
            }
            else if(location != ''){
                url += '?location=' + location;
            }
            window.location = url;
            
        }
        var days= ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
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
                        url:'".Yii::app()->createUrl('provider/timetable')."',//this is the request page of ajax
                        data:{branchID:selectedBranch, serviceID: selectedService, _date:currentDate},//data for throwing the expected url
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
            minDate: 0,    // this will be replaced with the ajax function call
            maxDate: new Date('$model->provider_booking_enddate')
        });
        //Provider View Page Settings
        var locationLinks = $('#location a');
        locationLinks.click(function(){
            locationLinks.removeClass('selected');
            $(this).addClass('selected');

            return false;
        });
        $('#datepicker').datepicker();
	"
        , CClientScript::POS_END);
?>
<section id='content' class='pad'>

    <div id='leftColumn'>
        <h1>Welcome to <?php echo $model->provider_name ?></h1>
        <img src='<?php echo $model->logoLarge ?>' id='providerLogo'/>

        <div id='description'>
            <h2>Description</h2>
            <p>
                Casa Spa brings the day spa into the comfort of your home.
                Our licensed beauty therapists transport everything needed to transform your space into a spa. 
                We offer a full range of spa treatments, spa packages and parties to meet the needs of unique individuals. 
                Our licensed beauty therapists transport everything needed to transform your space into a spa.
            </p>
        </div>

        <div id='location'>
            <h2>
                Location
                <span style="font-size: 12px;">Please select a location to view the available services</span>
            </h2>
            
            <ul>
                <?php
                foreach ($model->branches as $branch) {
                    echo "<li><a href='#' onclick=\"selectedBranch = $branch->branch_id;
                    
                    $.ajax({
                    url:'".Yii::app()->createUrl('provider/services')."',//this is the request page of ajax
                    data:{id:$branch->branch_id},//data for throwing the expected url
                    type:'GET',//you can also use GET method
                    dataType:'html',// you can also specify for the result for json or xml
                    success:function(response){
                         $('#services').html(response);
                         if(response.indexOf('No available services') !== -1){
                            $('#booking').hide();
                         };
                    },
                    error:function(){
                         alert('Failed request data from ajax page');
                }});\">"
                    . $branch->location->location_name . " <span>$branch->branch_address</span></a></li>";
                }
                ?>
            </ul>
        </div>

        <div id='services'>

        </div>

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

        

        
    </div>

    <div id='rightColumn'>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad1.jpg'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad2.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad4.gif'/></a>
        <a href='#'><img src='<?php echo Yii::app()->request->baseUrl; ?>/images/ads/ad3.gif'/></a>
    </div>

    <br class='clear'/>
</section>