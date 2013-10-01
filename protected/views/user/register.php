<?php
$this->breadcrumbs=array(
	'Register',
);

$this->layout = "column1";

/*
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);*/
?>

<h1 style="margin-bottom:20px; margin-top:10px;">Registration</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>