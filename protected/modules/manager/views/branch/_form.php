<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'branch-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<?php echo $form->dropDownList($model,'location_id',$dropdown); ?>
		<?php echo $form->error($model,'location_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'branch_address'); ?>
		<?php echo $form->textArea($model,'branch_address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'branch_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'branch_phone'); ?>
		<?php echo $form->textField($model,'branch_phone',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'branch_phone'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->