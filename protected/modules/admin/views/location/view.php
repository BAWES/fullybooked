<?php
$this->breadcrumbs=array(
	'Locations'=>array('index'),
	$model->location_name,
);

$this->menu=array(
	array('label'=>'Manage Locations', 'url'=>array('index')),
	array('label'=>'Create Location', 'url'=>array('create')),
	array('label'=>'Update Location', 'url'=>array('update', 'id'=>$model->location_id)),
	array('label'=>'Delete Location', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->location_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1><?php echo $model->location_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'location_id',
		'location_name',
	),
)); ?>
