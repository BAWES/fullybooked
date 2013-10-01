<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Providers', 'url'=>array('index')),
);
?>

<h1>Create Provider</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'categoryDropdown'=>$categoryDropdown)); ?>