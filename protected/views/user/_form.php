<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row" style="margin-bottom:5px;">
		<?php echo $form->labelEx($model,'user_email'); ?>
		<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'user_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_password'); ?>
		<?php echo $form->passwordField($model,'user_password',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'user_password'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

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

	<div class="row">
		<?php echo $form->labelEx($model,'user_mobile_num'); ?>
		<?php echo $form->textField($model,'user_mobile_num',array('size'=>20,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'user_mobile_num'); ?>
	</div>
	
	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->