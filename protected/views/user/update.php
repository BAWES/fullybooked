<?php
$this->breadcrumbs=array(
	'Account'=>array('user/view'),
	'Update Info',
);

$this->menu=array(
);
?>

<h1>Update Account Info</h1>

<?php echo $this->renderPartial('_updateform', array('model'=>$model)); ?>