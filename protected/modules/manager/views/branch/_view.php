<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('location.location_name')); ?>:</b>
	<?php echo CHtml::encode($data->location->location_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('branch_address')); ?>:</b>
	<?php echo CHtml::encode($data->branch_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('branch_phone')); ?>:</b>
	<?php echo CHtml::encode($data->branch_phone); ?>
	<br />

	<b><?php echo CHtml::link(CHtml::encode("View Branch Info"), array('view', 'id'=>$data->branch_id)); ?></b>
	<br />
	
</div>