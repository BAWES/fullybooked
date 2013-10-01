<?php
Yii::app()->clientScript->registerCoreScript('jquery');
$cs = Yii::app()->getClientScript();
$cs->registerScript('appointmentLevels',"
//main vars
var branch = null;
var date = null;
var service = null;

var dateSelection = $('#dateSelection');
var serviceSelection = $('#serviceSelection');
var employeeSelection = $('#employeeSelection');
var serviceList = $('#services');

//branch selected
$('#branches a').click(function(){
	var branchId = parseInt($(this).attr('href').replace('#',''));
	branch = branchId;
	date = null;
	service = null;
	
	serviceSelection.hide();
	employeeSelection.hide();
	
	dateSelection.slideDown();
	return false;
});

//on service selected
function serviceSelected(service){
	var serviceId = parseInt(service.attr('href').replace('#',''));
	service = serviceId;
	
	alert(service);
	//AJAX -> using servicesId, get all employees that offer that service in that branch
	//Services::model -> employees by relation and use where branchid = current branch selected
}

//date picked
function dateSelected(d){
	date=d;
	loadServices();
	serviceSelection.slideDown();
}

function loadServices(){
	service = null;
	if(branch && date){		
		$.ajax({
			type: 'GET',
			url: '".$this->createUrl('appointment/services')."',
			data: {'branch':branch, 'date':date}
		}).done(function(msg) {
			serviceList.html(msg);
			$('#services a').unbind().click(function(){serviceSelected($(this));});
		});
	}
}

",CClientscript::POS_END);
?>

<?php
$this->breadcrumbs=array(
	'Appointment',
	$provider->provider_name,
);?>

<h1>Book Appointment (<?php echo $provider->provider_name; ?>)</h1>

<ol>


<li id='branchSelection'><b>Select Branch</b>
	<ul id='branches'>
		<?php
		foreach($provider->branches AS $branch){
			echo "<li><a href='#".$branch->branch_id."'>".$branch->location->location_name." Branch</a></li>";
		}
		?>
	</ul>
</li>


<li id='dateSelection' style='display:none;'><b>Select Date</b>
	<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'bookingDate',
			'flat'=>true,
			//js options
			'options'=>array(
				'showAnim'=>'fold',
				'autoSize'=>false,
				'minDate'=>0,
				'dateFormat'=>'yy-mm-dd',
				'onSelect'=>"js:function(d,obj){dateSelected(d);}",
			),
		));
	?>
</li>


<li id='serviceSelection' style='display:none;'><b>Select service (show price/duration)</b>
	<ul id='services'>
		
	</ul>
</li>


<li id='employeeSelection' style='display:none;'><b>Select Employee/Timing</b>

</li>


</ol>