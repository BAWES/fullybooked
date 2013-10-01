<?php
$this->breadcrumbs=array(
	'Locations'=>array('index'),
	$model->location_id=>array('view','id'=>$model->location_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Locations', 'url'=>array('index')),
	array('label'=>'Create Location', 'url'=>array('create')),
	array('label'=>'View Location', 'url'=>array('view', 'id'=>$model->location_id)),
);
?>

<h1>Update Location <?php echo $model->location_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>