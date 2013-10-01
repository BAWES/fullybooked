<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'user_name'); ?>
		<?php echo $form->textField($model,'user_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'user_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'user_gender'); ?>
		<?php echo $form->dropDownList($model,'user_gender', array('male'=>'Male','female'=>'Female')); ?>
		<?php echo $form->error($model,'user_gender'); ?>
	</div>

	<div class="row">
	<?php echo $form->labelEx($model,'user_birth_date'); ?>
	<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'model'=>$model,
			'attribute'=>'user_birth_date',
			'value'=>$model->user_birth_date,
			//js options
			'options'=>array(
				'showAnim'=>'fold',
				'showButtonPanel'=>true,
				'autoSize'=>false,
				'dateFormat'=>'yy-mm-dd',
				'changeMonth'=>true,
				'changeYear' => true,
				'maxDate'=>-1,
				'yearRange'=>'-50:+0',
				'defaultDate'=>$model->user_birth_date,
			),
		));
	?>
	<?php echo $form->error($model,'user_birth_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->