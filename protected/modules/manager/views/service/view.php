<?php
$this->breadcrumbs=array(
	'Services'=>array('index'),
	$model->service_name,
);

$this->menu=array(
	array('label'=>'List Service', 'url'=>array('index')),
	array('label'=>'Create Service', 'url'=>array('create')),
	array('label'=>'Update Service', 'url'=>array('update', 'id'=>$model->service_id)),
	array('label'=>'Delete Service', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->service_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Service', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->service_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'service_name',
		array(
			'type'=>'raw',
			'value'=>$model->service_duration." minutes",
			'label'=>'Duration'
		),
		'service_price',
		'service_description',
	),
)); ?>

<h3 style='margin-top:30px;'>Employees offering the service</h3>
<ul>
<?php
if($model->employees){
	foreach($model->employees AS $employee){
		echo "<li><a href='".$this->createUrl('employee/view',array('id'=>$employee->employee_id))."'>".$employee->employee_name."</a></li>";
	}
}
?>
</ul>