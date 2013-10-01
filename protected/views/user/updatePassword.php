<?php
$this->breadcrumbs=array(
	'Account'=>array('user/view'),
	'Change Password',
);

$this->menu=array(
);
?>

<h1>Change Password</h1>

<?php echo $this->renderPartial('_updatepasswordform', array('model'=>$model)); ?>