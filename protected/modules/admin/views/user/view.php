<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->user_name,
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('index')),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->user_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'user_id',
		'user_email',
		'user_name',
		'user_gender',
		'user_birth_date',
		'user_mobile_num',
		'user_verif_code',
		'user_account_created',
	),
)); ?>

<?php

if($userActivities !== null){
	echo "<h3 style='margin-top:20px;'>Activities</h3>
	<table>
	<tr>
	<th>Time</th>
	<th>Route</th>
	<th>IP</th>
	<th>Device/Browser</th>
	</tr>
	";
	
	foreach($userActivities as $activity){
		$route = $activity->activity_route;
		$ip = $activity->activity_user_ip;
		$browser = $activity->activity_user_browser;
		$time = $activity->activity_datetime;
		
		echo "
		<tr>
		<td>$time</td><td>$route</td><td>$ip</td><td>$browser</td>
		</tr>
		";
	}
	
	echo "</table>";
}

if($model->sms !== null){
	echo "<h3 style='margin-top:20px;'>SMS</h3>
	<table>
	<tr>
	<th>Time</th>
	<th>Type</th>
	<th>Phone</th>
	<th>Message</th>
	<th>Status</th>
	</tr>
	";
	
	foreach($model->sms as $sms){
		$time = $sms->sms_time;
		$type = $sms->sms_type;
		$phone = $sms->sms_phone;
		$message = str_replace(array('+','%0a'),' ',$sms->sms_message);
		$response = substr($sms->sms_response,0,2);
		if($response != "OK") $response = "Problem";
		
		echo "
		<tr>
		<td>$time</td><td>$type</td><td>$phone</td><td>$message</td><td>$response</td>
		</tr>
		";
	}
	
	echo "</table>";
}
?>