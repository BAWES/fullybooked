<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salon-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id',$categoryDropdown); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'provider_logo'); ?>
		<?php echo $form->fileField($model,'provider_logo'); ?>
		<?php echo $form->error($model,'provider_logo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'provider_name'); ?>
		<?php echo $form->textField($model,'provider_name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'provider_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'provider_username'); ?>
		<?php echo $form->textField($model,'provider_username',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'provider_username'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'provider_booking_startdate'); ?>
	<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'=>$model,
			'attribute'=>'provider_booking_startdate',
			'value'=>$model->provider_booking_startdate,
			//js options
			'options'=>array(
				'showAnim'=>'fold',
				'showButtonPanel'=>true,
				'autoSize'=>false,
				'dateFormat'=>'yy-mm-dd',
				'minDate'=>-1,
				'defaultDate'=>$model->provider_booking_startdate,
			),
		));
	?>
	<?php echo $form->error($model,'provider_booking_startdate'); ?>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'provider_booking_enddate'); ?>
	<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'=>$model,
			'attribute'=>'provider_booking_enddate',
			'value'=>$model->provider_booking_enddate,
			//js options
			'options'=>array(
				'showAnim'=>'fold',
				'showButtonPanel'=>true,
				'autoSize'=>false,
				'minDate'=>0,
				'dateFormat'=>'yy-mm-dd',
				'defaultDate'=>$model->provider_booking_enddate,
			),
		));
	?>
	<?php echo $form->error($model,'provider_booking_enddate'); ?>
	</div>
	

	<div class="row">
		<?php echo $form->labelEx($model,'provider_contact_name'); ?>
		<?php echo $form->textField($model,'provider_contact_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'provider_contact_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'provider_contact_number'); ?>
		<?php echo $form->textField($model,'provider_contact_number',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'provider_contact_number'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'provider_maximum_branches'); ?>
		<?php echo $form->textField($model,'provider_maximum_branches',array('size'=>60,'maxlength'=>64,'placeholder'=>0)); ?>
		<?php echo $form->error($model,'provider_maximum_branches'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->