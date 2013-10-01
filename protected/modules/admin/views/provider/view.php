<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	$model->provider_name,
);

$this->menu=array(
	array('label'=>'Manage Providers', 'url'=>array('index')),
	array('label'=>'Create New Provider', 'url'=>array('create')),
	array('label'=>'Change Provider Password', 'url'=>array('changePassword', 'id'=>$model->provider_id)),
	array('label'=>'Update Provider', 'url'=>array('update', 'id'=>$model->provider_id)),
	array('label'=>'Delete Provider', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->provider_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->provider_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'type'=>'raw',
			'value'=>CHtml::image(Yii::app()->request->baseUrl.'/images/provider/thumb/'.$model->provider_logo),
			'label'=>'Logo'
		),
		'provider_id',
		'provider_name',
		'provider_username',
		array(
			'type'=>'raw',
			'value'=>'***',
			'label'=>'Password'
		),
		'provider_booking_startdate',
		'provider_booking_enddate',
		'provider_contact_name',
		'provider_contact_number',
		'provider_maximum_branches',
	),
)); ?>

<br/>
<h2>Branches</h2>
<?php
foreach($model->branches AS $branch){
	echo $branch->branch_address."<br/>";
}
?>

<br/>
<h2>Employees</h2>
<?php
foreach($model->employees AS $employee){
	echo $employee->employee_name."<br/>";
}
?>

<br/>
<h2>Services</h2>
<?php
foreach($model->services AS $service){
	echo $service->service_name." - KD ".$service->service_price."<br/>";
}
?>