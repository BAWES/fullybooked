<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'employee_id'); ?>
		<?php echo $form->textField($model,'employee_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'branch_id'); ?>
		<?php echo $form->textField($model,'branch_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_name'); ?>
		<?php echo $form->textField($model,'employee_name',array('size'=>60,'maxlength'=>160)); ?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'employee_workstart'); ?>
		<?php echo $form->textField($model,'employee_workstart'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_workend'); ?>
		<?php echo $form->textField($model,'employee_workend'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_breakstart'); ?>
		<?php echo $form->textField($model,'employee_breakstart'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_breakend'); ?>
		<?php echo $form->textField($model,'employee_breakend'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'employee_dayoff'); ?>
		<?php echo $form->textField($model,'employee_dayoff',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->