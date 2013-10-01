<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'service_duration',array('label'=>'Duration (Minutes)')); ?>
		<?php echo $form->textField($model,'service_duration'); ?>
		<?php echo $form->error($model,'service_duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_price',array('label'=>'Price (KD)')); ?>
		<?php echo $form->textField($model,'service_price'); ?>
		<?php echo $form->error($model,'service_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_name'); ?>
		<?php echo $form->textField($model,'service_name',array('size'=>60,'maxlength'=>160)); ?>
		<?php echo $form->error($model,'service_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_description'); ?>
		<?php echo $form->textArea($model,'service_description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'service_description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->