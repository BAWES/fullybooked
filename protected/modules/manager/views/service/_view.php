<div class="view">
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('service_name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->service_name), array('view', 'id'=>$data->service_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_description')); ?>:</b>
	<?php echo CHtml::encode($data->service_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_duration')); ?>:</b>
	<?php echo CHtml::encode($data->service_duration." minutes"); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_price')); ?>:</b>
	<?php echo CHtml::encode("KD ".$data->service_price); ?>
	<br />

	


</div>