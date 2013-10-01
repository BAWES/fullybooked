<?php
$this->breadcrumbs=array(
	'Account'=>array('user/view'),
	'Change Mobile Number',
);

$this->menu=array(
);
?>

<h1>Change Mobile Number</h1>

<p>If mobile number is changed, you will be required to re-verify by SMS</p>

<?php echo $this->renderPartial('_updatemobileform', array('model'=>$model)); ?>