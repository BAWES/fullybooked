<?php
$this->breadcrumbs=array(
	'Employees'=>array('index'),
	$model->employee_name,
);

$this->menu=array(
	array('label'=>'List Employee', 'url'=>array('index')),
	array('label'=>'Create Employee', 'url'=>array('create')),
	array('label'=>'Update Employee', 'url'=>array('update', 'id'=>$model->employee_id)),
	array('label'=>'Delete Employee', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->employee_id),'confirm'=>'Are you sure you want to delete this employee?')),
	array('label'=>'Manage Employee', 'url'=>array('admin')),
);
?>

<h1>Employee - <?php echo $model->employee_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'branch_id',
		'employee_name',
		'employee_workstart',
		'employee_workend',
		'employee_breakstart',
		'employee_breakend',
		'employee_dayoff',
	),
)); ?>

<br/>
<h2>Services Offered</h2>
<?php //IF KEEPING THIS IMPLEMENTATION THEN CONSIDER USING EAGER LOADING INSTEAD
	echo "<ul>";
	if($model->services){
		foreach($model->services AS $service){
			echo "<li><a href='".$this->createUrl('service/view',array('id'=>$service->service_id))."'>".$service->service_name."</a></li>";
		}
	}else echo "<li>No services offered</li>";
	echo "</ul>";
?>