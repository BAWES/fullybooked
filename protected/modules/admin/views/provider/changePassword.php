<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	$model->provider_id=>array('view','id'=>$model->provider_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Manage Providers', 'url'=>array('index')),
	array('label'=>'Create New Provider', 'url'=>array('create')),
	array('label'=>'View Provider', 'url'=>array('view', 'id'=>$model->provider_id)),
);
?>

<h1>Change Password for <?php echo $model->provider_name; ?></h1>

<?php echo $this->renderPartial('_changePwForm', array('model'=>$model)); ?>