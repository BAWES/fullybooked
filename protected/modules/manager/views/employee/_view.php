<div class="view">

<?php
/*
	$link = $this->createUrl('view', array('id'=>$data->employee_id));
	$imgUrl = $data->picThumb;
	echo "
	<div style='float:left; margin-right:5px; width:100px;'>
	<a href='$link'><img src='$imgUrl' /></a>
	</div>
	";
 * 
 */
?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->employee_name), array('view', 'id'=>$data->employee_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('branch_id')); ?>:</b>
	<?php echo CHtml::encode($data->branch->branch_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_workstart')); ?>:</b>
	<?php echo CHtml::encode($data->employee_workstart); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_workend')); ?>:</b>
	<?php echo CHtml::encode($data->employee_workend); ?>
	<br />
<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_breakstart')); ?>:</b>
	<?php echo CHtml::encode($data->employee_breakstart); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_breakend')); ?>:</b>
	<?php echo CHtml::encode($data->employee_breakend); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('employee_dayoff')); ?>:</b>
	<?php echo CHtml::encode($data->employee_dayoff); ?>
	<br />

*/ ?>
	<br style='clear:both;'/>
</div>