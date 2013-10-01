<?php
$this->breadcrumbs=array(
	'Branches'=>array('index'),
	$model->branch_id=>array('view','id'=>$model->branch_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Branch', 'url'=>array('index')),
	array('label'=>'Create Branch', 'url'=>array('create')),
	array('label'=>'View Branch', 'url'=>array('view', 'id'=>$model->branch_id)),
	array('label'=>'Manage Branch', 'url'=>array('admin')),
);
?>

<h1>Update Branch <?php echo $model->branch_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'dropdown'=>$dropdown)); ?>