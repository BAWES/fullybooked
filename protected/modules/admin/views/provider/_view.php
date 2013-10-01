<div class="view">
<?php
	$link = $this->createUrl('view', array('id'=>$data->provider_id));
	$imgUrl = $data->logoThumb;
	echo "
	<div style='float:left; margin-right:5px; width:100px;'>
	<a href='$link'><img src='$imgUrl' /></a>
	</div>
	";
?>
	 
	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->provider_id), array('view', 'id'=>$data->provider_id)); ?>
	<br />


	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_name')); ?>:</b>
	<?php echo CHtml::encode($data->provider_name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_username')); ?>:</b>
	<?php echo CHtml::encode($data->provider_username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_password')); ?>:</b>
	<?php echo CHtml::encode($data->provider_password); ?>
	<br />
	*/ ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_booking_startdate')); ?>:</b>
	<?php echo CHtml::encode($data->provider_booking_startdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_booking_enddate')); ?>:</b>
	<?php echo CHtml::encode($data->provider_booking_enddate); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_contact_name')); ?>:</b>
	<?php echo CHtml::encode($data->provider_contact_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('provider_contact_number')); ?>:</b>
	<?php echo CHtml::encode($data->provider_contact_number); ?>
	<br />

	*/ ?>
		
	<br style='clear:both;'/>
</div>